<?php

namespace Labstag\Lib;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Labstag\Entity\Block;
use Labstag\Entity\Chapter;
use Labstag\Entity\Groupe;
use Labstag\Entity\Menu;
use Labstag\Entity\Paragraph;
use Labstag\Entity\User;
use Labstag\Interfaces\DomainInterface;
use Labstag\Interfaces\EntityInterface;
use Labstag\Interfaces\EntityTrashInterface;
use Labstag\Repository\ParagraphRepository;
use RuntimeException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;
use Symfony\Component\Routing\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

abstract class AdminControllerLib extends ControllerLib
{

    protected string $urlHome = '';

    public function form(
        DomainInterface $domain,
        EntityInterface $entity,
        string $twig = 'admin/crud/form.html.twig',
        array $parameters = []
    ): Response
    {
        $this->modalAttachmentDelete();
        $formType = $domain->getType();
        $url      = $domain->getUrlAdmin();
        $this->denyAccessUnlessGranted(
            empty($entity->getId()) ? 'new' : 'edit',
            $entity
        );
        $this->setBtnViewUpdate($url, $entity);
        $form = $this->createForm($formType, $entity);
        $this->adminBtnService->addBtnSave(
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
            $repository = $domain->getRepository();
            $repository->add($entity);
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

        return $this->render(
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
        DomainInterface $domain,
        string $html,
        array $parameters = []
    ): Response
    {
        $url = $domain->getUrlAdmin();
        /** @var Request $request */
        $request   = $this->requeststack->getCurrentRequest();
        $all       = $request->attributes->all();
        $route     = $all['_route'];
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

        return $this->render(
            $html,
            $parameters
        );
    }

    public function modalAttachmentDelete(): void
    {
        $globals = $this->twigEnvironment->getGlobals();
        $modal   = $globals['modal'] ?? [];
        if (!is_array($modal)) {
            $modal = [];
        }

        $modal['attachmentdelete'] = true;
        $this->twigEnvironment->addGlobal('modal', $modal);
    }

    public function renderShowOrPreview(
        DomainInterface $domain,
        EntityInterface $entity,
        string $twigShow
    ): Response
    {
        /** @var EntityTrashInterface $entity */
        $url = $domain->getUrlAdmin();
        /** @var Request $request */
        $request      = $this->requeststack->getCurrentRequest();
        $routeCurrent = $request->get('_route');
        if (!is_string($routeCurrent)) {
            throw new AccessDeniedException();
        }

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

    protected function addNewBreadcrumb(
        Route $data,
        array $routeParam,
        string $route
    ): void
    {
        $compiledRoute   = $data->compile();
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

        $variables = $compiledRoute->getPathVariables();
        $params    = [];
        foreach ($variables as $variable) {
            if (isset($routeParam[$variable])) {
                $params[$variable] = $routeParam[$variable];
            }
        }

        if ((is_countable($variables) ? count($variables) : 0) != count($params)) {
            return;
        }

        $this->breadcrumbService->add(
            $title,
            $this->router->generate(
                $route,
                $params,
            )
        );
    }

    protected function addNewImport(
        EntityManagerInterface $entityManager,
        RepositoryLib $serviceEntityRepositoryLib,
        array $methods,
        string $routeType,
        array $url = [],
    ): void
    {
        $this->listOrTrashRouteTrashsetTrashIcon(
            $methods,
            $serviceEntityRepositoryLib,
            $url,
            $routeType,
            $entityManager
        );

        if (isset($url['new']) && 'trash' != $routeType) {
            $this->adminBtnService->addBtnNew(
                $url['new']
            );
        }

        if (isset($url['import']) && 'trash' != $routeType) {
            $this->adminBtnService->addBtnImport(
                $url['import']
            );
        }
    }

    protected function classEntity(EntityInterface $entity): string
    {
        $class = str_replace('Labstag\\Entity\\', '', (string) $entity::class);

        return strtolower($class);
    }

    protected function enableBtnGuard(EntityInterface $entity): bool
    {
        if ($entity instanceof User) {
            $routes = $this->guardService->getGuardRoutesForUser($entity);

            return 0 != count($routes);
        }

        if (!$entity instanceof Groupe) {
            return false;
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
        RepositoryLib $serviceEntityRepositoryLib
    ): void
    {
        $entity = strtolower(
            str_replace(
                'Labstag\\Entity\\',
                '',
                $serviceEntityRepositoryLib->getClassName()
            )
        );
        if (isset($url['list'])) {
            $this->adminBtnService->addBtnList(
                $url['list']
            );
        }

        if (isset($url['empty'])) {
            $this->adminBtnService->addBtnEmpty(
                [
                    'empty' => $url['empty'],
                    'list'  => $url['list'],
                ],
                $entity
            );
        }

        $globals = $this->twigEnvironment->getGlobals();
        $modal   = $globals['modal'] ?? [];
        if (!is_array($modal)) {
            $modal = [];
        }

        $modal['destroy'] = (isset($url['destroy']));
        $modal['restore'] = (isset($url['restore']));
        $this->twigEnvironment->addGlobal('modal', $modal);
        /** @var Request $request */
        $request     = $this->requeststack->getCurrentRequest();
        $all         = $request->attributes->all();
        $route       = $all['_route'];
        $routeParams = $all['_route_params'];

        $this->adminBtnService->addViderSelection(
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

        $this->adminBtnService->addRestoreSelection(
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

    protected function render(
        string $view,
        array $parameters = [],
        ?Response $response = null
    ): Response
    {
        $parameters = $this->generateMenus($parameters);
        $this->setBreadcrumbsPage();
        $parameters = $this->setTitleHeader($parameters);

        $parameters['btnadmin'] = $this->adminBtnService->get();

        return parent::render($view, $parameters, $response);
    }

    protected function searchForm(): array
    {
        return [];
    }

    protected function setBreadcrumbsPage(): void
    {
        $routeCollection     = $this->router->getRouteCollection();
        $requestContext      = $this->router->getContext();
        $traceableUrlMatcher = new TraceableUrlMatcher($routeCollection, $requestContext);
        /** @var Request $request */
        $request    = $this->requeststack->getCurrentRequest();
        $attributes = $request->attributes->all();
        $pathinfo   = $request->getPathInfo();
        $breadcrumb = $this->getBreadcrumb($traceableUrlMatcher, $pathinfo, []);
        $breadcrumb = array_reverse($breadcrumb);

        $all         = $routeCollection->all();
        $routeParams = $attributes['_route_params'];
        foreach ($breadcrumb as $row) {
            $name  = $row['name'];
            $route = $all[$name];
            $this->addNewBreadcrumb($route, $routeParams, $name);
        }

        $data = $this->breadcrumbService->get();
        $this->twigEnvironment->addGlobal('breadcrumbs', $data);
    }

    protected function setBtnDelete(array $url, EntityInterface $entity): void
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

        $this->adminBtnService->addBtnDelete(
            $entity,
            $urlsDelete,
            'Supprimer',
            [
                'id'     => $entity->getId(),
                'entity' => $this->classEntity($entity),
            ]
        );
    }

    protected function setBtnDeleties(
        string $routeType,
        string $route,
        array $routeParams,
        RepositoryLib $serviceEntityRepositoryLib
    ): void
    {
        if ('trash' == $routeType) {
            return;
        }

        $this->adminBtnService->addSupprimerSelection(
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
                                (string) $serviceEntityRepositoryLib->getClassName()
                            )
                        ),
                    ],
                ],
            ],
            'deleties'
        );
    }

    protected function setBtnGuard(array $url, EntityInterface $entity): void
    {
        if (!isset($url['guard']) || !$this->enableBtnGuard($entity)) {
            return;
        }

        $this->adminBtnService->addBtnGuard(
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

        $this->adminBtnService->addBtnList(
            $url['list'],
            'Liste',
        );
    }

    protected function setBtnListOrTrash(
        string $routeType,
        DomainInterface $domain
    ): void
    {
        $url                        = $domain->getUrlAdmin();
        $serviceEntityRepositoryLib = $domain->getRepository();
        /** @var Request $request */
        $request     = $this->requeststack->getCurrentRequest();
        $all         = $request->attributes->all();
        $route       = $all['_route'];
        $routeParams = $all['_route_params'];
        $methods     = $this->getMethodsList();
        $this->addNewImport($this->entityManager, $serviceEntityRepositoryLib, $methods, $routeType, $url);
        $this->setBtnDeleties($routeType, $route, $routeParams, $serviceEntityRepositoryLib);
    }

    protected function setBtnShow(array $url, EntityInterface $entity): void
    {
        if (!isset($url['show'])) {
            return;
        }

        $this->adminBtnService->addBtnShow(
            $url['show'],
            'Show',
            [
                'id' => $entity->getId(),
            ]
        );
    }

    protected function setBtnViewUpdate(
        array $url,
        EntityInterface $entity
    ): void
    {
        $this->setBtnList($url);
        if (empty($entity->getId())) {
            return;
        }

        $functions = [
            'setBtnShow',
            'setBtnGuard',
            'setBtnDelete',
        ];

        foreach ($functions as $function) {
            /** @var callable $callable */
            $callable = [
                $this,
                $function,
            ];
            call_user_func_array($callable, [$url, $entity]);
        }
    }

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

    protected function setPagination(
        string $routeType,
        DomainInterface $domain
    ): PaginationInterface
    {
        $serviceEntityRepositoryLib = $domain->getRepository();
        $methods                    = $this->getMethodsList();
        $method                     = $methods[$routeType];
        /** @var Request $request */
        $request = $this->requeststack->getCurrentRequest();
        $query   = $request->query;
        $get     = $query->all();
        $limit   = $query->getInt('limit', 10);
        /** @var callable $callable */
        $callable = [
            $serviceEntityRepositoryLib,
            $method,
        ];

        return $this->paginator->paginate(
            call_user_func($callable, $get),
            $query->getInt('page', 1),
            $limit
        );
    }

    protected function setPositionEntity(Request $request, string $entityclass): void
    {
        $position = $request->request->get('position');
        if (!empty($position)) {
            $position = json_decode((string) $position, true, 512, JSON_THROW_ON_ERROR);
        }

        if (is_iterable($position)) {
            foreach ($position as $row) {
                $id       = $row['id'];
                $position = (int) $row['position'];
                /** @var RepositoryLib $repository */
                $repository = $this->repositoryService->get($entityclass);
                /** @var Block|Chapter|Menu $entity */
                $entity = $repository->find($id);
                if (!is_null($entity)) {
                    $entity->setPosition($position + 1);
                    $repository->add($entity);
                }
            }
        }
    }

    protected function setSearchForms(
        array $parameters,
        DomainInterface $domain
    ): array
    {
        /** @var Request $request */
        $request = $this->requeststack->getCurrentRequest();
        $query   = $request->query;
        $get     = $query->all();
        $limit   = $query->getInt('limit', 10);
        $form    = $domain->getSearchForm();
        if ('' == $form) {
            return $parameters;
        }

        $get              = $query->all();
        $searchLib        = $domain->getSearchData();
        $searchLib->limit = $limit;
        $searchLib->search($get, $this->entityManager);
        $route = $request->get('_route');
        if (!is_string($route)) {
            return $parameters;
        }

        $url        = $this->generateUrl($route);
        $searchForm = $this->createForm(
            $form,
            $searchLib,
            [
                'attr'   => ['id' => 'searchform'],
                'action' => $url,
            ]
        );

        $parameters['searchform'] = $searchForm;

        return $parameters;
    }

    protected function setTrashIcon(
        array $methods,
        RepositoryLib $serviceEntityRepositoryLib,
        array $url,
        EntityManagerInterface $entityManager
    ): void
    {
        $methodTrash      = $methods['trash'];
        $filterCollection = $entityManager->getFilters();
        $filterCollection->disable('softdeleteable');
        /** @var callable $callable */
        $callable = [
            $serviceEntityRepositoryLib,
            $methodTrash,
        ];
        $trash  = call_user_func($callable, []);
        if (!$trash instanceof QueryBuilder) {
            throw new RuntimeException('trash must be a QueryBuilder');
        }

        $query  = $trash->getQuery();
        $result = $query->getResult();
        $total  = is_countable($result) ? count($result) : 0;
        $filterCollection->enable('softdeleteable');
        if (0 != $total) {
            $this->adminBtnService->addBtnTrash(
                $url['trash']
            );
        }

        $globals = $this->twigEnvironment->getGlobals();
        $modal   = $globals['modal'] ?? [];
        if (!is_array($modal)) {
            $modal = [];
        }

        $modal['delete']   = (isset($url['delete']));
        $modal['workflow'] = (isset($url['workflow']));

        $this->twigEnvironment->addGlobal('modal', $modal);
    }

    protected function showOrPreviewadd(
        array $url,
        string $routeType,
        EntityInterface $entity
    ): void
    {
        $functions = [
            'showOrPreviewaddBtnList',
            'showOrPreviewaddBtnGuard',
            'showOrPreviewaddBtnTrash',
            'showOrPreviewaddBtnEdit',
            'showOrPreviewaddBtnRestore',
            'showOrPreviewaddBtnDestroy',
        ];

        foreach ($functions as $function) {
            /** @var callable $callable */
            $callable = [
                $this,
                $function,
            ];
            call_user_func_array($callable, [$url, $routeType, $entity]);
        }
    }

    protected function showOrPreviewaddBtnDestroy(
        array $url,
        string $routeType,
        EntityInterface $entity
    ): void
    {
        if (!(isset($url['destroy']) && 'preview' == $routeType)) {
            return;
        }

        $this->adminBtnService->addBtnDestroy(
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

    protected function showOrPreviewaddBtnEdit(
        array $url,
        string $routeType,
        EntityInterface $entity
    ): void
    {
        if (!(isset($url['edit']) && 'show' == $routeType) || !$this->isGranted('edit', $entity)) {
            return;
        }

        $this->adminBtnService->addBtnEdit(
            $url['edit'],
            'Editer',
            [
                'id' => $entity->getId(),
            ]
        );
    }

    protected function showOrPreviewaddBtnGuard(
        array $url,
        string $routeType,
        EntityInterface $entity
    ): void
    {
        if (!(isset($url['guard']) && 'show' == $routeType) || !$this->enableBtnGuard($entity)) {
            return;
        }

        $this->adminBtnService->addBtnGuard(
            $url['guard'],
            'Guard',
            [
                'id' => $entity->getId(),
            ]
        );
    }

    protected function showOrPreviewaddBtnList(
        array $url,
        string $routeType,
        EntityInterface $entity
    ): void
    {
        unset($entity);
        if (!(isset($url['list']) && 'show' == $routeType)) {
            return;
        }

        $this->adminBtnService->addBtnList(
            $url['list'],
            'Liste',
        );
    }

    protected function showOrPreviewaddBtnRestore(
        array $url,
        string $routeType,
        EntityInterface $entity
    ): void
    {
        if (isset($url['restore']) && 'preview' == $routeType) {
            $this->adminBtnService->addBtnRestore(
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

    protected function showOrPreviewaddBtnTrash(
        array $url,
        string $routeType,
        EntityInterface $entity
    ): void
    {
        unset($entity);
        if (!(isset($url['trash']) && 'preview' == $routeType)) {
            return;
        }

        $this->adminBtnService->addBtnTrash(
            $url['trash'],
            'Trash',
        );
    }

    private function addInBreadcrumb(array $breadcrumb, array $trace): array
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

    private function getBreadcrumb(
        TraceableUrlMatcher $traceableUrlMatcher,
        string $pathinfo,
        array $breadcrumb
    ): array
    {
        $traces = $traceableUrlMatcher->getTraces($pathinfo);
        foreach ($traces as $trace) {
            $testadmin = 0 != substr_count((string) $trace['name'], 'admin');
            if (TraceableUrlMatcher::ROUTE_MATCHES == $trace['level'] && $testadmin) {
                $breadcrumb = $this->addInBreadcrumb($breadcrumb, $trace);
            }
        }

        if (0 != substr_count((string) $pathinfo, '/')) {
            $newpathinfo = substr((string) $pathinfo, 0, strrpos((string) $pathinfo, '/') + 1);
            if ($newpathinfo === $pathinfo) {
                $newpathinfo = substr((string) $pathinfo, 0, (int) strrpos((string) $pathinfo, '/'));
            }

            $breadcrumb = $this->getBreadcrumb(
                $traceableUrlMatcher,
                $newpathinfo,
                $breadcrumb
            );
        }

        return $breadcrumb;
    }

    private function listOrTrashRouteTrashsetTrashIcon(
        array $methods,
        RepositoryLib $serviceEntityRepositoryLib,
        array $url,
        string $routeType,
        EntityManagerInterface $entityManager
    ): void
    {
        if ('trash' == $routeType) {
            $this->listOrTrashRouteTrash($url, $serviceEntityRepositoryLib);

            return;
        }

        if (isset($url['trash'])) {
            $this->setTrashIcon($methods, $serviceEntityRepositoryLib, $url, $entityManager);
        }
    }

    private function modalParagraphs(): void
    {
        $globals = $this->twigEnvironment->getGlobals();
        $modal   = $globals['modal'] ?? [];
        if (!is_array($modal)) {
            $modal = [];
        }

        $modal['paragraphs'] = true;
        $this->twigEnvironment->addGlobal('modal', $modal);
    }

    private function setPositionParagraphs(): void
    {
        /** @var Request $request */
        $request    = $this->requeststack->getCurrentRequest();
        $paragraphs = $request->request->all('paragraphs');
        if (!is_iterable($paragraphs)) {
            return;
        }

        /** @var ParagraphRepository $repository */
        $repository = $this->repositoryService->get(Paragraph::class);
        foreach ($paragraphs as $id => $position) {
            /** @var int $position */
            $paragraph = $repository->find($id);
            if (!$paragraph instanceof Paragraph) {
                continue;
            }

            $paragraph->setPosition($position);
            $repository->add($paragraph);
        }
    }

    private function setTitleHeader(array $parameters): array
    {
        /** @var Request $request */
        $request = $this->requeststack->getCurrentRequest();
        $all     = $request->attributes->all();
        $route   = $all['_route'];
        $headers = $this->domainService->getTitles();
        $header  = '';
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
