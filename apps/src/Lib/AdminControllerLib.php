<?php

namespace Labstag\Lib;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Labstag\Entity\Attachment;
use Labstag\Entity\Paragraph;
use Labstag\Entity\User;
use Labstag\Reader\UploadAnnotationReader;
use Labstag\Repository\AttachmentRepository;
use Labstag\RequestHandler\AttachmentRequestHandler;
use Labstag\Service\AttachFormService;
use Labstag\Singleton\AdminBtnSingleton;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

abstract class AdminControllerLib extends ControllerLib
{

    protected AttachmentRequestHandler $attachmentRH;

    protected ?AdminBtnSingleton $btns = null;

    protected FlashBagInterface $flashbag;

    protected RouterInterface $router;

    protected TokenStorageInterface $token;

    protected string $urlHome = '';

    public function form(
        AttachFormService $service,
        RequestHandlerLib $handler,
        string $formType,
        object $entity,
        string $twig = 'admin/crud/form.html.twig',
        array $parameters = []
    ): Response
    {
        $url = $this->getUrlAdmin();
        dump($entity);
        $this->denyAccessUnlessGranted(
            empty($entity->getId()) ? 'new' : 'edit',
            $entity
        );
        $this->setBtnViewUpdate($url, $entity);
        $oldEntity = clone $entity;
        $form      = $this->createForm($formType, $entity);
        $this->btnInstance()->addBtnSave(
            $form->getName(),
            empty($entity->getId()) ? 'Ajouter' : 'Sauvegarder'
        );
        $form->handleRequest($this->requeststack->getCurrentRequest());
        if ($form->isSubmitted() && $form->isValid()) {
            $this->setPositionParagraphs();
            $this->upload(
                $service->getUploadAnnotationReader(),
                $entity
            );
            $handler->handle($oldEntity, $entity);
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
        $entity,
        string $html,
        array $parameters = []
    ): Response
    {
        $repository = $this->getRepository($entity);
        $url        = $this->getUrlAdmin();
        $request    = $this->requeststack->getCurrentRequest();
        $all        = $request->attributes->all();
        $route      = $all['_route'];
        $routeType  = (0 != substr_count((string) $route, 'trash')) ? 'trash' : 'all';
        $this->setBtnListOrTrash($repository, $routeType);
        $pagination = $this->setPagination($repository, $routeType);

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
        $parameters = $this->setSearchForms($parameters);

        return $this->renderForm(
            $html,
            $parameters
        );
    }

    public function modalAttachmentDelete(): void
    {
        $twig                      = $this->twig;
        $globals                   = $twig->getGlobals();
        $modal                     = $globals['modal'] ?? [];
        $modal['attachmentdelete'] = true;
        $twig->addGlobal('modal', $modal);
    }

    protected function render(
        string $view,
        array $parameters = [],
        ?Response $response = null
    ): Response
    {
        $parameters = $this->generateMenus($parameters);
        $this->setBreadcrumbsPage();
        $request = $this->requeststack->getCurrentRequest();
        $all     = $request->attributes->all();
        $route   = $all['_route'];
        $headers = $this->setHeaderTitle();
        $header  = '';
        foreach ($headers as $key => $title) {
            if ($key == $route) {
                $header = $title;

                break;
            }

            if (0 != substr_count((string) $route, $key)) {
                $header = $title;
            }
        }

        if (!empty($header)) {
            $parameters['headerTitle'] = $header;
        }

        $parameters['btnadmin'] = $this->btnInstance()->get();

        return parent::render($view, $parameters, $response);
    }

    public function renderShowOrPreview(
        object $entity,
        string $twigShow
    ): Response
    {
        $url          = $this->getUrlAdmin();
        $routeCurrent = $this->requeststack->getCurrentRequest()->get('_route');
        $routeType    = (0 != substr_count((string) $routeCurrent, 'preview')) ? 'preview' : 'show';
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

    protected function addNewBreadcrumb($data, $routeParam, $route)
    {
        $compiled        = $data->compile();
        $breadcrumbTitle = $this->setBreadcrumbsData();
        $title           = '';
        foreach ($breadcrumbTitle as $row) {
            if ($row['route'] == $route) {
                $title = $row['title'];

                break;
            }
        }

        if ('' == $title) {
            return;
        }

        $variables = $compiled->getPathVariables();
        $params    = [];
        foreach ($variables as $key) {
            if (isset($routeParam[$key])) {
                $params[$key] = $routeParam[$key];
            }
        }

        if ((is_countable($variables) ? count($variables) : 0) != count($params)) {
            return;
        }

        $this->setSingletons()->add(
            $title,
            $this->routerInterface->generate(
                $route,
                $params,
            )
        );
    }

    protected function addNewImport(
        EntityManagerInterface $entityManager,
        ServiceEntityRepositoryLib $repository,
        array $methods,
        string $routeType,
        array $url = [],
    )
    {
        $this->listOrTrashRouteTrashsetTrashIcon(
            $methods,
            $repository,
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
                $this->twig,
                $this->routerInterface,
                $this->tokenStorage,
                $this->csrfTokenManager,
                $this->guardService
            );
        }

        return $this->btns;
    }

    protected function classEntity($entity)
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

    protected function getMethodsList(): array
    {
        return [
            'trash' => 'findTrashForAdmin',
            'all'   => 'findAllForAdmin',
        ];
    }

    protected function getUrlAdmin(): array
    {
        return [];
    }

    protected function isRouteEnable(
        string $route
    )
    {
        return $this->guardService->guardRoute(
            $route,
            $this->tokenStorage->getToken()
        );
    }

    protected function listOrTrashRouteTrash(
        array $url,
        ServiceEntityRepositoryLib $repository
    )
    {
        $entity = strtolower(
            str_replace(
                'Labstag\\Entity\\',
                '',
                $repository->getClassName()
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

        $twig             = $this->twig;
        $globals          = $twig->getGlobals();
        $modal            = $globals['modal'] ?? [];
        $modal['destroy'] = (isset($url['destroy']));
        $modal['restore'] = (isset($url['restore']));
        $twig->addGlobal('modal', $modal);

        $request     = $this->requeststack->getCurrentRequest();
        $all         = $request->attributes->all();
        $route       = $all['_route'];
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
                                $repository->getClassName()
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
                                $repository->getClassName()
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

    protected function renderForm(string $view, array $parameters = [], ?Response $response = null): Response
    {
        $parameters = $this->generateMenus($parameters);

        return parent::renderForm($view, $parameters, $response);
    }

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

    protected function setBreadcrumbsData(): array
    {
        return [
            [
                'title'        => $this->translator->trans('admin.title', [], 'admin.breadcrumb'),
                'route'        => 'admin',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPage()
    {
        $collection  = $this->routerInterface->getRouteCollection();
        $context     = $this->routerInterface->getContext();
        $matcher     = new TraceableUrlMatcher($collection, $context);
        $request     = $this->requeststack->getCurrentRequest();
        $attributes  = $request->attributes->all();
        $pathinfo    = $request->getPathInfo();
        $breadcrumb  = $this->getBreadcrumb($matcher, $pathinfo, []);
        $breadcrumb  = array_reverse($breadcrumb);

        $all         = $collection->all();
        $routeParams = $attributes['_route_params'];
        foreach ($breadcrumb as $row) {
            $name  = $row['name'];
            $route = $all[$name];
            $this->addNewBreadcrumb($route, $routeParams, $name);
        }

        $data = $this->setSingletons()->get();
        $this->twig->addGlobal('breadcrumbs', $data);
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

    protected function setBtnDeleties($routeType, $route, $routeParams, $repository)
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

    protected function setBtnListOrTrash($repository, $routeType)
    {
        $url         = $this->getUrlAdmin();
        $request     = $this->requeststack->getCurrentRequest();
        $all         = $request->attributes->all();
        $route       = $all['_route'];
        $routeParams = $all['_route_params'];
        $methods     = $this->getMethodsList();
        $this->addNewImport($this->entityManager, $repository, $methods, $routeType, $url);
        $this->setBtnDeleties($routeType, $route, $routeParams, $repository);
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

    protected function setHeaderTitle(): array
    {
        return [
            'admin' => $this->translator->trans('admin.title', [], 'admin.header'),
        ];
    }

    protected function setPagination($repository, $routeType)
    {
        $methods = $this->getMethodsList();
        $method  = $methods[$routeType];
        $query   = $this->requeststack->getCurrentRequest()->query;
        $get     = $query->all();
        $limit   = $query->getInt('limit', 10);

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
                $id         = $row['id'];
                $position   = (int) $row['position'];
                $repository = $this->getRepository($entityclass);
                $entity     = $repository->find($id);
                if (!is_null($entity)) {
                    $entity->setPosition($position + 1);
                    $repository->add($entity);
                }
            }
        }
    }

    protected function setSearchForms($parameters)
    {
        $query  = $this->requeststack->getCurrentRequest()->query;
        $get    = $query->all();
        $limit  = $query->getInt('limit', 10);
        $search = $this->searchForm();
        if (!(0 != count($search) && array_key_exists('form', $search) && array_key_exists('data', $search))) {
            return $parameters;
        }

        $get         = $query->all();
        $data        = $search['data'];
        $data->limit = $limit;
        $data->search($get, $this->entityManager);
        $route      = $this->requeststack->getCurrentRequest()->get('_route');
        $url        = $this->generateUrl($route);
        $searchForm = $this->createForm(
            $search['form'],
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
        /** @var EntityManager $entityManager */
        $methodTrash = $methods['trash'];
        $filters     = $entityManager->getFilters();
        $filters->disable('softdeleteable');

        $trash  = call_user_func([$repository, $methodTrash], []);
        $result = $trash->getQuery()->getResult();
        $total  = is_countable($result) ? count($result) : 0;
        $filters->enable('softdeleteable');
        if (0 != $total) {
            $this->btnInstance()->addBtnTrash(
                $url['trash']
            );
        }

        $twig              = $this->twig;
        $globals           = $twig->getGlobals();
        $modal             = $globals['modal'] ?? [];
        $modal['delete']   = (isset($url['delete']));
        $modal['workflow'] = (isset($url['workflow']));

        $twig->addGlobal('modal', $modal);
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

    protected function showOrPreviewaddBtnDestroy($url, $routeType, $entity)
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

    protected function showOrPreviewaddBtnEdit($url, $routeType, $entity)
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
    )
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

    protected function showOrPreviewaddBtnList($url, $routeType)
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

    protected function showOrPreviewaddBtnTrash($url, $routeType)
    {
        if (!(isset($url['trash']) && 'preview' == $routeType)) {
            return;
        }

        $this->btnInstance()->addBtnTrash(
            $url['trash'],
            'Trash',
        );
    }

    protected function upload(UploadAnnotationReader $uploadAnnotReader, $entity)
    {
        if (!$uploadAnnotReader->isUploadable($entity)) {
            return;
        }

        $annotations = $uploadAnnotReader->getUploadableFields($entity);
        foreach ($annotations as $property => $annotation) {
            $accessor = PropertyAccess::createPropertyAccessor();
            $file     = $accessor->getValue($entity, $property);
            if (!$file instanceof UploadedFile) {
                continue;
            }

            $attachment = $this->setAttachment(
                $accessor,
                $entity,
                $annotation
            );
            $old        = clone $attachment;

            $filename = $file->getClientOriginalName();
            $path     = $this->getParameter('file_directory').'/'.$annotation->getPath();
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
    )
    {
        if ('trash' == $routeType) {
            $this->listOrTrashRouteTrash($url, $repository);

            return;
        }

        if (isset($url['trash'])) {
            $this->setTrashIcon($methods, $repository, $url, $entityManager);
        }
    }

    private function setPositionParagraphs()
    {
        $request    = $this->requeststack->getCurrentRequest();
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
}
