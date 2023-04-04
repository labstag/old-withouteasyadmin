<?php

namespace Labstag\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Exception;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\Groupe;
use Labstag\Entity\Paragraph;
use Labstag\Entity\User;
use Labstag\Interfaces\DomainInterface;
use Labstag\Interfaces\EntityInterface;
use Labstag\Lib\RepositoryLib;
use RuntimeException;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Matcher\TraceableUrlMatcher;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

class AdminService
{
    /**
     * @var int
     */
    final public const STATUSRESPONSE = 200;

    protected ?DomainInterface $domain = null;

    public function __construct(
        protected BlockService $blockService,
        protected BreadcrumbService $breadcrumbService,
        protected RouterInterface $router,
        protected MenuService $menuService,
        protected SessionService $sessionService,
        protected TranslatorInterface $translator,
        protected GuardService $guardService,
        protected AttachFormService $attachFormService,
        protected AuthorizationCheckerInterface $authorizationChecker,
        protected FormFactoryInterface $formFactory,
        protected PaginatorInterface $paginator,
        protected Environment $twigEnvironment,
        protected EntityManagerInterface $entityManager,
        protected AdminBtnService $adminBtnService,
        protected RepositoryService $repositoryService,
        protected RequestStack $requeststack,
        protected DomainService $domainService
    )
    {
    }

    public function edit(
        EntityInterface $entity,
        array $parameters = []
    ): Response
    {
        return $this->editOrNew('edit', $entity, $parameters);
    }

    public function getDomain(): DomainInterface
    {
        return $this->domain;
    }

    public function index(
        array $parameters = []
    ): Response
    {
        return $this->listOrTrash('index', $parameters);
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

    public function new(
        array $parameters = []
    ): Response
    {

        $class  = $this->domain->getEntity();
        $entity = new $class();

        return $this->editOrNew('new', $entity, $parameters);
    }

    public function preview(
        EntityInterface $entity
    ): Response
    {
        return $this->showOrPreview($entity, 'preview');
    }

    public function render(
        string $view,
        array $parameters = [],
        ?Response $response = null
    ): Response
    {
        $parameters = $this->generateMenus($parameters);
        $this->setBreadcrumbsPage();
        $parameters = $this->setTitleHeader($parameters);

        $parameters['btnadmin'] = $this->adminBtnService->get();

        $content = $this->renderView($view, $parameters);

        $response ??= new Response();

        if (self::STATUSRESPONSE === $response->getStatusCode()) {
            foreach ($parameters as $parameter) {
                if ($parameter instanceof FormInterface && $parameter->isSubmitted() && !$parameter->isValid()) {
                    $response->setStatusCode(422);

                    break;
                }
            }
        }

        $response->setContent($content);

        return $response;
    }

    public function setDomain(string $entity): void
    {
        $domainLib = $this->domainService->getDomain($entity);
        if (!$domainLib instanceof DomainInterface) {
            throw new Exception('Domain not found');
        }

        $this->domain = $domainLib;
    }

    public function show(
        EntityInterface $entity
    ): Response
    {
        return $this->showOrPreview($entity, 'show');
    }

    public function trash(
        array $parameters = []
    ): Response
    {
        return $this->listOrTrash('trash', $parameters);
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
            $this->generateUrl(
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
        $path = explode('\\', $entity::class);

        return strtolower(array_pop($path));
    }

    protected function createForm(
        string $type = FormType::class,
        mixed $data = null,
        array $options = []
    ): FormInterface
    {
        return $this->formFactory->create($type, $data, $options);
    }

    protected function denyAccessUnlessGranted(
        mixed $attribute,
        mixed $subject = null,
        string $message = 'Access Denied.'
    ): void
    {
        if (!$this->isGranted($attribute, $subject)) {
            $accessDeniedException = new AccessDeniedException($message, null);
            $accessDeniedException->setAttributes([$attribute]);
            $accessDeniedException->setSubject($subject);

            throw $accessDeniedException;
        }
    }

    protected function editOrNew(
        string $type,
        EntityInterface $entity,
        array $parameters = []
    ): Response
    {
        $templates = $this->domain->getTemplates();
        $template  = (array_key_exists($type, $templates)) ? $templates[$type] : 'admin/crud/form.html.twig';

        $this->modalAttachmentDelete();
        $formType = $this->domain->getType();
        $url      = $this->domain->getUrlAdmin();
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
            $repository = $this->domain->getRepository();
            $repository->save($entity);
            $this->sessionService->flashBagAdd(
                'success',
                $this->translator->trans('data.save')
            );
            if (isset($url['list'])) {
                return $this->redirectToRoute($url['list']);
            }
        }

        $parameters = array_merge(
            $parameters,
            [
                'entity' => $entity,
                'form'   => $form,
            ]
        );

        return $this->render(
            $template,
            $parameters
        );
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

    protected function generateUrl(
        string $route,
        array $parameters = [],
        int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH
    ): string
    {
        return $this->router->generate($route, $parameters, $referenceType);
    }

    protected function isGranted(
        string $method,
        EntityInterface $entity
    ): bool
    {
        return $this->authorizationChecker->isGranted($method, $entity);
    }

    protected function listOrTrashRouteTrash(
        array $url,
        RepositoryLib $serviceEntityRepositoryLib
    ): void
    {
        $path   = explode('\\', $serviceEntityRepositoryLib->getClassName());
        $entity = strtolower(array_pop($path));
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

        $path   = explode('\\', $serviceEntityRepositoryLib->getClassName());
        $entity = strtolower(array_pop($path));
        $this->adminBtnService->addViderSelection(
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
        $this->adminBtnService->addRestoreSelection(
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

    protected function redirect(string $url, int $status = 302): RedirectResponse
    {
        return new RedirectResponse($url, $status);
    }

    protected function redirectToRoute(string $route, array $parameters = [], int $status = 302): RedirectResponse
    {
        return $this->redirect($this->generateUrl($route, $parameters), $status);
    }

    protected function renderView(string $view, array $parameters = []): string
    {
        foreach ($parameters as $k => $v) {
            if ($v instanceof FormInterface) {
                $parameters[$k] = $v->createView();
            }
        }

        return $this->twigEnvironment->render($view, $parameters);
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

        $path   = explode('\\', $serviceEntityRepositoryLib->getClassName());
        $entity = strtolower(array_pop($path));
        $this->adminBtnService->addSupprimerSelection(
            [
                'redirect' => [
                    'href'   => $route,
                    'params' => $routeParams,
                ],
                'url'      => [
                    'href'   => 'api_action_deleties',
                    'params' => ['entity' => $entity],
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
        string $routeType
    ): void
    {
        $url                        = $this->domain->getUrlAdmin();
        $serviceEntityRepositoryLib = $this->domain->getRepository();
        /** @var Request $request */
        $request     = $this->requeststack->getCurrentRequest();
        $all         = $request->attributes->all();
        $route       = $all['_route'];
        $routeParams = $all['_route_params'];
        $methods     = $this->domain->getMethodsList();
        $this->addNewImport($serviceEntityRepositoryLib, $methods, $routeType, $url);
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
        string $routeType
    ): PaginationInterface
    {
        $serviceEntityRepositoryLib = $this->domain->getRepository();
        $methods                    = $this->domain->getMethodsList();
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
                    $repository->save($entity);
                }
            }
        }
    }

    protected function setSearchForms(
        array $parameters
    ): array
    {
        /** @var Request $request */
        $request = $this->requeststack->getCurrentRequest();
        $query   = $request->query;
        $get     = $query->all();
        $limit   = $query->getInt('limit', 10);
        $form    = $this->domain->getSearchForm();
        if ('' == $form) {
            return $parameters;
        }

        $get              = $query->all();
        $searchLib        = $this->domain->getSearchData();
        $searchLib->limit = $limit;
        $searchLib->search($get, $this->repositoryService);
        $route = $request->get('_route');
        if (!is_string($route)) {
            return $parameters;
        }

        $url        = $this->router->generate($route);
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

    private function listOrTrash(
        string $type,
        array $parameters = []
    ): Response
    {
        $templates = $this->domain->getTemplates();
        if (!array_key_exists($type, $templates)) {
            throw new Exception('Template not found');
        }

        $url = $this->domain->getUrlAdmin();
        /** @var Request $request */
        $request   = $this->requeststack->getCurrentRequest();
        $all       = $request->attributes->all();
        $route     = $all['_route'];
        $routeType = (0 != substr_count((string) $route, 'trash')) ? 'trash' : 'all';
        $this->setBtnListOrTrash($routeType);
        $pagination = $this->setPagination($routeType);

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

        $template = $templates[$type];

        return $this->render(
            $template,
            $parameters
        );
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

        /** @var ParagraphRepository $repositoryLib */
        $repositoryLib = $this->repositoryService->get(Paragraph::class);
        foreach ($paragraphs as $id => $position) {
            /** @var int $position */
            $paragraph = $repositoryLib->find($id);
            if (!$paragraph instanceof Paragraph) {
                continue;
            }

            $paragraph->setPosition($position);
            $repositoryLib->save($paragraph);
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

    private function showOrPreview(
        EntityInterface $entity,
        string $type
    ): Response
    {
        $templates = $this->domain->getTemplates();
        if (!array_key_exists($type, $templates)) {
            throw new Exception('Template not found');
        }

        $template = $templates[$type];

        /** @var EntityTrashInterface $entity */
        $url = $this->domain->getUrlAdmin();
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

        $parameters = ['entity' => $entity];

        return $this->render(
            $template,
            $parameters
        );
    }
}
