<?php

namespace Labstag\Lib;

use Doctrine\ORM\QueryBuilder;
use Labstag\Entity\Block;
use Labstag\Entity\Chapter;
use Labstag\Entity\Groupe;
use Labstag\Entity\Menu;
use Labstag\Entity\User;
use Labstag\Interfaces\DomainInterface;
use Labstag\Interfaces\EntityInterface;
use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;
use Symfony\Component\Routing\Route;

abstract class AdminControllerLib extends ControllerLib
{

    protected string $urlHome = '';

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
            $routeType
        );

        if (isset($url['new']) && 'trash' != $routeType) {
            $this->btnService->addBtnNew(
                $url['new']
            );
        }

        if (isset($url['import']) && 'trash' != $routeType) {
            $this->btnService->addBtnImport(
                $url['import']
            );
        }
    }

    protected function classEntity(EntityInterface $entity): string
    {
        $path = explode('\\', $entity::class);

        return strtolower(array_pop($path));
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
        $path   = explode('\\', $serviceEntityRepositoryLib->getClassName());
        $entity = strtolower(array_pop($path));
        if (isset($url['list'])) {
            $this->btnService->addBtnList(
                $url['list']
            );
        }

        if (isset($url['empty'])) {
            $this->btnService->addBtnEmpty(
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

        $path   = explode('\\', $serviceEntityRepositoryLib->getClassName());
        $entity = strtolower(array_pop($path));
        $this->btnService->addViderSelection(
            [
                'redirect' => [
                    'href'   => $route,
                    'params' => $routeParams,
                ],
                'url'      => [
                    'href'   => 'api_action_destroies',
                    'params' => ['entity' => $entity],
                ],
            ],
            'destroies'
        );

        $path   = explode('\\', $serviceEntityRepositoryLib->getClassName());
        $entity = strtolower(array_pop($path));
        $this->btnService->addRestoreSelection(
            [
                'redirect' => [
                    'href'   => $route,
                    'params' => $routeParams,
                ],
                'url'      => [
                    'href'   => 'api_action_restories',
                    'params' => ['entity' => $entity],
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

        $parameters['btnadmin'] = $this->btnService->get();

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
                    $repository->save($entity);
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
        $searchLib->search($get, $this->repositoryService);
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
        array $url
    ): void
    {
        $methodTrash      = $methods['trash'];
        $filterCollection = $this->entityManager->getFilters();
        $filterCollection->disable('softdeleteable');
        /** @var callable $callable */
        $callable = [
            $serviceEntityRepositoryLib,
            $methodTrash,
        ];
        $trash = call_user_func($callable, []);
        if (!$trash instanceof QueryBuilder) {
            throw new RuntimeException('trash must be a QueryBuilder');
        }

        $query  = $trash->getQuery();
        $result = $query->getResult();
        $total  = is_countable($result) ? count($result) : 0;
        $filterCollection->enable('softdeleteable');
        if (0 != $total) {
            $this->btnService->addBtnTrash(
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

        $this->btnService->addBtnDestroy(
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

        $this->btnService->addBtnEdit(
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

        $this->btnService->addBtnGuard(
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

        $this->btnService->addBtnList(
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
            $this->btnService->addBtnRestore(
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

        $this->btnService->addBtnTrash(
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
        string $routeType
    ): void
    {
        if ('trash' == $routeType) {
            $this->listOrTrashRouteTrash($url, $serviceEntityRepositoryLib);

            return;
        }

        if (isset($url['trash'])) {
            $this->setTrashIcon($methods, $serviceEntityRepositoryLib, $url);
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
