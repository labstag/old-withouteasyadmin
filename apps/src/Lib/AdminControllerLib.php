<?php

namespace Labstag\Lib;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Labstag\Entity\Attachment;
use Labstag\Entity\Paragraph;
use Labstag\Entity\User;
use Labstag\Repository\AttachmentRepository;
use Labstag\Singleton\AdminBtnSingleton;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

abstract class AdminControllerLib extends ControllerLib
{

    protected ?AdminBtnSingleton $btns = null;

    protected string $urlHome = '';

    public function form(
        $domain,
        object $entity,
        string $twig = 'admin/crud/form.html.twig',
        array $parameters = []
    ): Response
    {
        $this->modalAttachmentDelete();
        $requestHandlerLib = $domain->getRequestHandler();
        $formType = $domain->getType();
        $url = $domain->getUrlAdmin();
        $this->denyAccessUnlessGranted(
            empty($entity->getId()) ? 'new' : 'edit',
            $entity
        );
        $this->setBtnViewUpdate($url, $entity);
        $oldEntity = clone $entity;
        $form = $this->createForm($formType, $entity);
        $this->btnInstance()->addBtnSave(
            $form->getName(),
            empty($entity->getId()) ? 'Ajouter' : 'Sauvegarder'
        );
        if ($form->has('paragraph')) {
            $this->modalParagraphs();
        }

        $form->handleRequest($this->requeststack->getCurrentRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            $this->setPositionParagraphs();
            $this->attachFormService->upload($entity);
            $requestHandlerLib->handle($oldEntity, $entity);
            $this->sessionService->flashBagAdd(
                'success',
                $this->translator->trans('data.save')
            );
            if (isset($url['list'])) {
                return new RedirectResponse(
                    $this->generateUrl($url['list'])
                );
            }
        }

        return $this->renderForm(
            $twig,
            array_merge(
                $parameters,
                [
                    'entity' => $entity,
                    'form'   => $form,
                ]
            )
        );
    }

    public function listOrTrash(
        $domain,
        string $html,
        array $parameters = []
    ): Response
    {
        $url = $domain->getUrlAdmin();
        $request = $this->requeststack->getCurrentRequest();
        $all = $request->attributes->all();
        $route = $all['_route'];
        $routeType = (0 != substr_count((string) $route, 'trash')) ? 'trash' : 'all';
        $this->setBtnListOrTrash($routeType, $domain);
        $pagination = $this->setPagination($routeType, $domain);

        if ('trash' == $routeType && 0 == $pagination->count()) {
            throw new AccessDeniedException();
        }

        $parameters = array_merge(
            $parameters,
            [
                'pagination' => $pagination,
                'actions'    => $url,
            ]
        );
        $parameters = $this->setSearchForms($parameters, $domain);

        return $this->renderForm(
            $html,
            $parameters
        );
    }

    public function modalAttachmentDelete(): void
    {
        $globals = $this->environment->getGlobals();
        $modal = $globals['modal'] ?? [];
        $modal['attachmentdelete'] = true;
        $this->environment->addGlobal('modal', $modal);
    }

    public function renderShowOrPreview(
        $domain,
        object $entity,
        string $twigShow
    ): Response
    {
        $url = $domain->getUrlAdmin();
        $routeCurrent = $this->requeststack->getCurrentRequest()->get('_route');
        $routeType = (0 != substr_count((string) $routeCurrent, 'preview')) ? 'preview' : 'show';
        $this->showOrPreviewadd($url, $routeType, $entity);

        if (isset($url['delete']) && 'show' == $routeType) {
            $this->setBtnDelete($url, $entity);
        }

        if ('preview' == $routeType && is_null($entity->getDeletedAt())) {
            throw new AccessDeniedException();
        }

        return $this->render(
            $twigShow,
            ['entity' => $entity]
        );
    }

    protected function addNewBreadcrumb($data, $routeParam, $route): void
    {
        $compiled = $data->compile();
        $breadcrumbTitle = array_merge(
            $this->setHeaderTitle(),
            $this->domainService->getTitles()
        );
        $title = '';
        foreach ($breadcrumbTitle as $key => $value) {
            if ($key == $route) {
                $title = $value;

                break;
            }
        }

        if ('' == $title) {
            return;
        }

        $variables = $compiled->getPathVariables();
        $params = [];
        foreach ($variables as $variable) {
            if (isset($routeParam[$variable])) {
                $params[$variable] = $routeParam[$variable];
            }
        }

        if ((is_countable($variables) ? count($variables) : 0) != count($params)) {
            return;
        }

        $this->setSingletons()->add(
            $title,
            $this->router->generate(
                $route,
                $params,
            )
        );
    }

    protected function addNewImport(
        EntityManagerInterface $entityManager,
        ServiceEntityRepositoryLib $serviceEntityRepositoryLib,
        array $methods,
        string $routeType,
        array $url = [],
    )
    {
        $this->listOrTrashRouteTrashsetTrashIcon(
            $methods,
            $serviceEntityRepositoryLib,
            $url,
            $routeType,
            $entityManager
        );

        if (isset($url['new']) && 'trash' != $routeType) {
            $this->btnInstance()->addBtnNew(
                $url['new']
            );
        }

        if (isset($url['import']) && 'trash' != $routeType) {
            $this->btnInstance()->addBtnImport(
                $url['import']
            );
        }
    }

    protected function btnInstance()
    {
        if (is_null($this->btns)) {
            $this->btns = AdminBtnSingleton::getInstance();
        }

        if (!$this->btns->isInit()) {
            $this->btns->setConf(
                $this->environment,
                $this->router,
                $this->tokenStorage,
                $this->csrfTokenManager,
                $this->guardService
            );
        }

        return $this->btns;
    }

    protected function classEntity($entity): string
    {
        $class = str_replace('Labstag\\Entity\\', '', (string) $entity::class);

        return strtolower($class);
    }

    protected function enableBtnGuard($entity): bool
    {
        if ($entity instanceof User) {
            $routes = $this->guardService->getGuardRoutesForUser($entity);

            return 0 != count($routes);
        }

        $routes = $this->guardService->getGuardRoutesForGroupe($entity);

        return 0 != count($routes);
    }

    /**
     * @return array<string, string>
     */
    protected function getMethodsList(): array
    {
        return [
            'trash' => 'findTrashForAdmin',
            'all'   => 'findAllForAdmin',
        ];
    }

    protected function isRouteEnable(
        string $route
    ): bool
    {
        return $this->guardService->guardRoute(
            $route,
            $this->tokenStorage->getToken()
        );
    }

    protected function listOrTrashRouteTrash(
        array $url,
        ServiceEntityRepositoryLib $serviceEntityRepositoryLib
    )
    {
        $environment = null;
        $entity = strtolower(
            str_replace(
                'Labstag\\Entity\\',
                '',
                $serviceEntityRepositoryLib->getClassName()
            )
        );
        if (isset($url['list'])) {
            $this->btnInstance()->addBtnList(
                $url['list']
            );
        }

        if (isset($url['empty'])) {
            $this->btnInstance()->addBtnEmpty(
                [
                    'empty' => $url['empty'],
                    'list'  => $url['list'],
                ],
                $entity
            );
        }

        $twig = $this->environment;
        $globals = $twig->getGlobals();
        $modal = $globals['modal'] ?? [];
        $modal['destroy'] = (isset($url['destroy']));
        $modal['restore'] = (isset($url['restore']));
        $environment->addGlobal('modal', $modal);

        $request = $this->requeststack->getCurrentRequest();
        $all = $request->attributes->all();
        $route = $all['_route'];
        $routeParams = $all['_route_params'];

        $this->btnInstance()->addViderSelection(
            [
                'redirect' => [
                    'href'   => $route,
                    'params' => $routeParams,
                ],
                'url'      => [
                    'href'   => 'api_action_destroies',
                    'params' => [
                        'entity' => strtolower(
                            str_replace(
                                'Labstag\\Entity\\',
                                '',
                                $serviceEntityRepositoryLib->getClassName()
                            )
                        ),
                    ],
                ],
            ],
            'destroies'
        );

        $this->btnInstance()->addRestoreSelection(
            [
                'redirect' => [
                    'href'   => $route,
                    'params' => $routeParams,
                ],
                'url'      => [
                    'href'   => 'api_action_restories',
                    'params' => [
                        'entity' => strtolower(
                            str_replace(
                                'Labstag\\Entity\\',
                                '',
                                $serviceEntityRepositoryLib->getClassName()
                            )
                        ),
                    ],
                ],
            ],
            'restories'
        );
    }

    protected function moveFile($file, $path, $filename, $attachment, $old)
    {
        $file->move(
            $path,
            $filename
        );
        $file = $path.'/'.$filename;

        $this->fileService->setAttachment($file, $attachment, $old);
    }

    protected function render(
        string $view,
        array $parameters = [],
        ?Response $response = null
    ): Response
    {
        $parameters = $this->generateMenus($parameters);
        $this->setBreadcrumbsPage();
        $parameters = $this->setTitleHeader($parameters);

        $parameters['btnadmin'] = $this->btnInstance()->get();

        return parent::render($view, $parameters, $response);
    }

    protected function renderForm(string $view, array $parameters = [], ?Response $response = null): Response
    {
        $parameters = $this->generateMenus($parameters);

        return parent::renderForm($view, $parameters, $response);
    }

    /**
     * @return mixed[]
     */
    protected function searchForm(): array
    {
        return [];
    }

    protected function setAttachment(
        $accessor,
        $entity,
        $annotation
    ): Attachment
    {
        $attachmentField = $accessor->getValue($entity, $annotation->getFilename());
        if (is_null($attachmentField)) {
            return new Attachment();
        }

        /** @var AttachmentRepository $repository */
        $repository = $this->getRepository(Attachment::class);
        $attachment = $repository->findOneBy(['id' => $attachmentField->getId()]);
        if (!$attachment instanceof Attachment) {
            $attachment = new Attachment();
        }

        return $attachment;
    }

    protected function setBreadcrumbsPage()
    {
        $routeCollection = $this->router->getRouteCollection();
        $requestContext = $this->router->getContext();
        $traceableUrlMatcher = new TraceableUrlMatcher($routeCollection, $requestContext);
        $request = $this->requeststack->getCurrentRequest();
        $attributes = $request->attributes->all();
        $pathinfo = $request->getPathInfo();
        $breadcrumb = $this->getBreadcrumb($traceableUrlMatcher, $pathinfo, []);
        $breadcrumb = array_reverse($breadcrumb);

        $all = $routeCollection->all();
        $routeParams = $attributes['_route_params'];
        foreach ($breadcrumb as $row) {
            $name = $row['name'];
            $route = $all[$name];
            $this->addNewBreadcrumb($route, $routeParams, $name);
        }

        $data = $this->setSingletons()->get();
        $this->environment->addGlobal('breadcrumbs', $data);
    }

    protected function setBtnDelete(array $url, object $entity): void
    {
        if (!isset($url['delete'])) {
            return;
        }

        $urlsDelete = [
            'delete' => $url['delete'],
        ];
        if (isset($url['list'])) {
            $urlsDelete['list'] = $url['list'];
        }

        $this->btnInstance()->addBtnDelete(
            $entity,
            $urlsDelete,
            'Supprimer',
            [
                'id'     => $entity->getId(),
                'entity' => $this->classEntity($entity),
            ]
        );
    }

    protected function setBtnDeleties($routeType, $route, $routeParams, $repository): void
    {
        if ('trash' == $routeType) {
            return;
        }

        $this->btnInstance()->addSupprimerSelection(
            [
                'redirect' => [
                    'href'   => $route,
                    'params' => $routeParams,
                ],
                'url'      => [
                    'href'   => 'api_action_deleties',
                    'params' => [
                        'entity' => strtolower(
                            str_replace(
                                'Labstag\\Entity\\',
                                '',
                                (string) $repository->getClassName()
                            )
                        ),
                    ],
                ],
            ],
            'deleties'
        );
    }

    protected function setBtnGuard(array $url, object $entity): void
    {
        if (!isset($url['guard']) || !$this->enableBtnGuard($entity)) {
            return;
        }

        $this->btnInstance()->addBtnGuard(
            $url['guard'],
            'Guard',
            [
                'id' => $entity->getId(),
            ]
        );
    }

    protected function setBtnList(array $url): void
    {
        if (!isset($url['list'])) {
            return;
        }

        $this->btnInstance()->addBtnList(
            $url['list'],
            'Liste',
        );
    }

    protected function setBtnListOrTrash(string $routeType, $domain)
    {
        $url = $domain->getUrlAdmin();
        $serviceEntityRepositoryLib = $domain->getRepository();
        $request = $this->requeststack->getCurrentRequest();
        $all = $request->attributes->all();
        $route = $all['_route'];
        $routeParams = $all['_route_params'];
        $methods = $this->getMethodsList();
        $this->addNewImport($this->entityManager, $serviceEntityRepositoryLib, $methods, $routeType, $url);
        $this->setBtnDeleties($routeType, $route, $routeParams, $serviceEntityRepositoryLib);
    }

    protected function setBtnShow(array $url, object $entity): void
    {
        if (!isset($url['show'])) {
            return;
        }

        $this->btnInstance()->addBtnShow(
            $url['show'],
            'Show',
            [
                'id' => $entity->getId(),
            ]
        );
    }

    protected function setBtnViewUpdate(
        array $url,
        object $entity
    ): void
    {
        $this->setBtnList($url);
        if (empty($entity->getId())) {
            return;
        }

        $this->setBtnShow($url, $entity);
        $this->setBtnGuard($url, $entity);
        $this->setBtnDelete($url, $entity);
    }

    /**
     * @return mixed[]
     */
    protected function setHeaderTitle(): array
    {
        return [
            'admin'        => $this->translator->trans('admin.title', [], 'admin.header'),
            'admin_oauth'  => $this->translator->trans('oauth.title', [], 'admin.header'),
            'admin_param'  => $this->translator->trans('param.title', [], 'admin.header'),
            'admin_profil' => $this->translator->trans('profil.title', [], 'admin.header'),
            'admin_trash'  => $this->translator->trans('trash.title', [], 'admin.header'),
        ];
    }

    protected function setPagination($routeType, $domain): PaginationInterface
    {
        $repository = $domain->getRepository();
        $methods = $this->getMethodsList();
        $method = $methods[$routeType];
        $query = $this->requeststack->getCurrentRequest()->query;
        $get = $query->all();
        $limit = $query->getInt('limit', 10);

        return $this->paginator->paginate(
            call_user_func([$repository, $method], $get),
            $query->getInt('page', 1),
            $limit
        );
    }

    protected function setPositionEntity($request, $entityclass)
    {
        $position = $request->request->get('position');
        if (!empty($position)) {
            $position = json_decode((string) $position, true, 512, JSON_THROW_ON_ERROR);
        }

        if (is_array($position)) {
            foreach ($position as $row) {
                $id = $row['id'];
                $position = (int) $row['position'];
                $repository = $this->getRepository($entityclass);
                $entity = $repository->find($id);
                if (!is_null($entity)) {
                    $entity->setPosition($position + 1);
                    $repository->add($entity);
                }
            }
        }
    }

    protected function setSearchForms($parameters, $domain)
    {
        $query = $this->requeststack->getCurrentRequest()->query;
        $get = $query->all();
        $limit = $query->getInt('limit', 10);
        $form = $domain->getSearchForm();
        if (is_null($form)) {
            return $parameters;
        }

        $get = $query->all();
        $data = $domain->getSearchData();
        $data->limit = $limit;
        $data->search($get, $this->entityManager);
        $route = $this->requeststack->getCurrentRequest()->get('_route');
        $url = $this->generateUrl($route);
        $searchForm = $this->createForm(
            $form,
            $data,
            [
                'attr'   => ['id' => 'searchform'],
                'action' => $url,
            ]
        );

        $parameters['searchform'] = $searchForm;

        return $parameters;
    }

    protected function setTrashIcon(
        $methods,
        $repository,
        $url,
        EntityManagerInterface $entityManager
    )
    {
        $methodTrash = $methods['trash'];
        $filterCollection = $entityManager->getFilters();
        $filterCollection->disable('softdeleteable');

        $trash = call_user_func([$repository, $methodTrash], []);
        $result = $trash->getQuery()->getResult();
        $total = is_countable($result) ? count($result) : 0;
        $filterCollection->enable('softdeleteable');
        if (0 != $total) {
            $this->btnInstance()->addBtnTrash(
                $url['trash']
            );
        }

        $globals = $this->environment->getGlobals();
        $modal = $globals['modal'] ?? [];
        $modal['delete'] = (isset($url['delete']));
        $modal['workflow'] = (isset($url['workflow']));

        $this->environment->addGlobal('modal', $modal);
    }

    protected function showOrPreviewadd(array $url, string $routeType, $entity): void
    {
        $this->showOrPreviewaddBtnList($url, $routeType);
        $this->showOrPreviewaddBtnGuard($url, $routeType, $entity);
        $this->showOrPreviewaddBtnTrash($url, $routeType);
        $this->showOrPreviewaddBtnEdit($url, $routeType, $entity);
        $this->showOrPreviewaddBtnRestore($url, $routeType, $entity);
        $this->showOrPreviewaddBtnDestroy($url, $routeType, $entity);
    }

    protected function showOrPreviewaddBtnDestroy($url, $routeType, $entity): void
    {
        if (!(isset($url['destroy']) && 'preview' == $routeType)) {
            return;
        }

        $this->btnInstance()->addBtnDestroy(
            $entity,
            [
                'destroy' => $url['destroy'],
                'list'    => $url['trash'],
            ],
            'Destroy',
            [
                'id'     => $entity->getId(),
                'entity' => $this->classEntity($entity),
            ]
        );
    }

    protected function showOrPreviewaddBtnEdit($url, $routeType, $entity): void
    {
        if (!(isset($url['edit']) && 'show' == $routeType) || !$this->isGranted('edit', $entity)) {
            return;
        }

        $this->btnInstance()->addBtnEdit(
            $url['edit'],
            'Editer',
            [
                'id' => $entity->getId(),
            ]
        );
    }

    protected function showOrPreviewaddBtnGuard(
        $url,
        $routeType,
        $entity
    ): void
    {
        if (!(isset($url['guard']) && 'show' == $routeType) || !$this->enableBtnGuard($entity)) {
            return;
        }

        $this->btnInstance()->addBtnGuard(
            $url['guard'],
            'Guard',
            [
                'id' => $entity->getId(),
            ]
        );
    }

    protected function showOrPreviewaddBtnList($url, $routeType): void
    {
        if (!(isset($url['list']) && 'show' == $routeType)) {
            return;
        }

        $this->btnInstance()->addBtnList(
            $url['list'],
            'Liste',
        );
    }

    protected function showOrPreviewaddBtnRestore($url, $routeType, $entity)
    {
        if (isset($url['restore']) && 'preview' == $routeType) {
            $this->btnInstance()->addBtnRestore(
                $entity,
                [
                    'restore' => $url['restore'],
                    'list'    => $url['trash'],
                ],
                'Restore',
                [
                    'id'     => $entity->getId(),
                    'entity' => $this->classEntity($entity),
                ]
            );
        }
    }

    protected function showOrPreviewaddBtnTrash($url, $routeType): void
    {
        if (!(isset($url['trash']) && 'preview' == $routeType)) {
            return;
        }

        $this->btnInstance()->addBtnTrash(
            $url['trash'],
            'Trash',
        );
    }

    protected function upload($entity): void
    {
        $uploadAnnotationReader = $this->attachFormService->getUploadAnnotationReader();
        if (!$uploadAnnotationReader->isUploadable($entity)) {
            return;
        }

        $annotations = $uploadAnnotationReader->getUploadableFields($entity);
        foreach ($annotations as $property => $annotation) {
            $accessor = PropertyAccess::createPropertyAccessor();
            $file = $accessor->getValue($entity, $property);
            if (!$file instanceof UploadedFile) {
                continue;
            }

            $attachment = $this->setAttachment(
                $accessor,
                $entity,
                $annotation
            );
            $old = clone $attachment;

            $filename = $file->getClientOriginalName();
            $path = $this->getParameter('file_directory').'/'.$annotation->getPath();
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }

            $this->moveFile($file, $path, $filename, $attachment, $old);
            $accessor->setValue($entity, $annotation->getFilename(), $attachment);
        }
    }

    private function addInBreadcrumb($breadcrumb, $trace)
    {
        $add = true;
        foreach ($breadcrumb as $row) {
            if ($row['name'] == $trace['name']) {
                $add = false;

                break;
            }
        }

        if ($add) {
            $breadcrumb[] = $trace;
        }

        return $breadcrumb;
    }

    /**
     * @return mixed[]
     */
    private function generateMenus(array $parameters = []): array
    {
        return array_merge(
            $parameters,
            [
                'allmenu' => $this->menuService->createMenus(),
            ]
        );
    }

    private function getBreadcrumb($matcher, $pathinfo, $breadcrumb)
    {
        $traces = $matcher->getTraces($pathinfo);
        foreach ($traces as $trace) {
            $testadmin = 0 != substr_count((string) $trace['name'], 'admin');
            if (TraceableUrlMatcher::ROUTE_MATCHES == $trace['level'] && $testadmin) {
                $breadcrumb = $this->addInBreadcrumb($breadcrumb, $trace);
            }
        }

        if (0 != substr_count((string) $pathinfo, '/')) {
            $newpathinfo = substr((string) $pathinfo, 0, strrpos((string) $pathinfo, '/') + 1);
            if ($newpathinfo == $pathinfo) {
                $newpathinfo = substr((string) $pathinfo, 0, strrpos((string) $pathinfo, '/'));
            }

            $breadcrumb = $this->getBreadcrumb(
                $matcher,
                $newpathinfo,
                $breadcrumb
            );
        }

        return $breadcrumb;
    }

    private function listOrTrashRouteTrashsetTrashIcon(
        $methods,
        $repository,
        $url,
        $routeType,
        EntityManagerInterface $entityManager
    ): void
    {
        if ('trash' == $routeType) {
            $this->listOrTrashRouteTrash($url, $repository);

            return;
        }

        if (isset($url['trash'])) {
            $this->setTrashIcon($methods, $repository, $url, $entityManager);
        }
    }

    private function modalParagraphs(): void
    {
        $globals = $this->environment->getGlobals();
        $modal = $globals['modal'] ?? [];
        $modal['paragraphs'] = true;
        $this->environment->addGlobal('modal', $modal);
    }

    private function setPositionParagraphs(): void
    {
        $request = $this->requeststack->getCurrentRequest();
        $paragraphs = $request->request->all('paragraphs');
        if (!is_array($paragraphs)) {
            return;
        }

        $repository = $this->getRepository(Paragraph::class);
        foreach ($paragraphs as $id => $position) {
            $paragraph = $repository->find($id);
            if (!$paragraph instanceof Paragraph) {
                continue;
            }

            $paragraph->setPosition($position);
            $repository->add($paragraph);
        }
    }

    private function setTitleHeader($parameters)
    {
        $request = $this->requeststack->getCurrentRequest();
        $all = $request->attributes->all();
        $route = $all['_route'];
        $headers = $this->domainService->getTitles();
        $header = '';
        foreach ($headers as $key => $title) {
            if ($key == $route) {
                $header = $title;

                break;
            }

            if (0 != substr_count((string) $route, (string) $key)) {
                $header = $title;
            }
        }

        if (!empty($header)) {
            $parameters['headerTitle'] = $header;
        }

        return $parameters;
    }
}
