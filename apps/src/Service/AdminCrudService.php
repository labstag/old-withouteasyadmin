<?php

namespace Labstag\Service;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Labstag\Lib\AdminControllerLib;
use Labstag\Lib\ServiceEntityRepositoryLib;
use Labstag\Service\AdminBoutonService;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Twig\Environment;

class AdminCrudService
{

    private AdminBoutonService $adminBoutonService;

    private PaginatorInterface $paginator;

    private RequestStack $requestStack;

    private Request $request;

    private FormFactoryInterface $formFactory;

    private RouterInterface $router;

    private CsrfTokenManagerInterface $csrfTokenManager;

    private EntityManagerInterface $entityManager;

    private SessionInterface $session;

    private EventDispatcherInterface $dispatcher;

    private Environment $twig;

    private AdminControllerLib $controller;

    protected string $headerTitle = '';

    protected string $urlHome = '';

    public function __construct(
        Environment $twig,
        AdminBoutonService $adminBoutonService,
        PaginatorInterface $paginator,
        RequestStack $requestStack,
        FormFactoryInterface $formFactory,
        RouterInterface $router,
        CsrfTokenManagerInterface $csrfTokenManager,
        EntityManagerInterface $entityManager,
        SessionInterface $session,
        EventDispatcherInterface $dispatcher
    )
    {
        $this->twig             = $twig;
        $this->dispatcher       = $dispatcher;
        $this->session          = $session;
        $this->entityManager    = $entityManager;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->router           = $router;
        $this->formFactory      = $formFactory;
        $this->requestStack     = $requestStack;
        /** @var Request $request */
        $request                  = $this->requestStack->getCurrentRequest();
        $this->request            = $request;
        $this->paginator          = $paginator;
        $this->adminBoutonService = $adminBoutonService;
    }

    public function addBreadcrumbs(array $breadcrumbs): void
    {
        BreadcrumbsService::getInstance()->add($breadcrumbs);
    }

    public function setPage($header, $url)
    {
        $this->headerTitle = $header;
        $this->urlHome     = $url;
    }

    private function setBtnList(array $url): void
    {
        if (!isset($url['list'])) {
            return;
        }

        $this->adminBoutonService->addBtnList(
            $url['list'],
            'Liste',
        );
    }

    private function setBtnViewUpdate(array $url, object $entity): void
    {
        $this->setBtnList($url);
        $this->setBtnShow($url, $entity);
        $this->setBtnDelete($url, $entity);
    }

    private function setBtnShow(array $url, object $entity): void
    {
        if (!isset($url['show'])) {
            return;
        }

        $this->adminBoutonService->addBtnShow(
            $url['show'],
            'Show',
            [
                'id' => $entity->getId(),
            ]
        );
    }

    private function setBtnDelete(array $url, object $entity): void
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

        $this->adminBoutonService->addBtnDelete(
            $entity,
            $urlsDelete,
            'Supprimer',
            [
                'id' => $entity->getId(),
            ]
        );
    }

    public function setController(AdminControllerLib $controller): void
    {
        $this->controller = $controller;
    }

    public function listOrTrash(
        ServiceEntityRepositoryLib $repository,
        array $methods,
        string $html,
        array $url = [],
        array $actions = []
    ): Response
    {
        $routeCurrent = $this->request->get('_route');
        $routeType    = (0 != substr_count($routeCurrent, 'trash')) ? 'trash' : 'all';
        $method       = $methods[$routeType];

        if ('trash' == $routeType) {
            $breadcrumb = [
                'Trash' => $this->router->generate(
                    'admin_adresseuser_trash'
                ),
            ];
            $this->addBreadcrumbs($breadcrumb);
            if (isset($url['list'])) {
                $this->adminBoutonService->addBtnList(
                    $url['list']
                );
            }

            if (isset($url['empty'])) {
                $this->adminBoutonService->addBtnEmpty(
                    [
                        'empty' => $url['empty'],
                        'list' => $url['list']
                    ]
                );
            }
            if (isset($actions['destroy'])) {
                $this->twig->addGlobal(
                    'modalDestroy',
                    true
                );
            }
            if (isset($actions['restore'])) {
                $this->twig->addGlobal(
                    'modalRestore',
                    true
                );
            }
        } elseif (isset($url['trash'])) {
            $this->adminBoutonService->addBtnTrash(
                $url['trash']
            );
            if (isset($actions['delete'])) {
                $this->twig->addGlobal(
                    'modalDelete',
                    true
                );
            }
        }

        if (isset($url['new'])) {
            $this->adminBoutonService->addBtnNew(
                $url['new']
            );
        }

        $pagination = $this->paginator->paginate(
            $repository->$method(),
            $this->request->query->getInt('page', 1),
            10
        );

        return $this->controller->render(
            $html,
            [
                'pagination' => $pagination,
                'actions'    => $actions,
            ]
        );
    }

    public function showOrPreview(
        object $entity,
        string $twigShow,
        array $url = []
    ): Response
    {
        $routeCurrent = $this->request->get('_route');
        $routeType    = (0 != substr_count($routeCurrent, 'preview')) ? 'preview' : 'show';
        if ('preview' == $routeType && isset($url['trash'])) {
            $breadcrumb = [
                'Trash' => $this->router->generate(
                    $url['trash']
                ),
            ];
            $this->addBreadcrumbs($breadcrumb);
        }

        $breadcrumb = [
            $routeType => $this->router->generate(
                $routeCurrent,
                [
                    'id' => $entity->getId(),
                ]
            ),
        ];
        $this->addBreadcrumbs($breadcrumb);

        if (isset($url['list']) && 'show' == $routeType) {
            $this->adminBoutonService->addBtnList(
                $url['list'],
                'Liste',
            );
        }

        if (isset($url['trash']) && 'preview' == $routeType) {
            $this->adminBoutonService->addBtnTrash(
                $url['trash'],
                'Trash',
            );
        }

        if (isset($url['edit']) && 'show' == $routeType) {
            $this->adminBoutonService->addBtnEdit(
                $url['edit'],
                'Editer',
                [
                    'id' => $entity->getId(),
                ]
            );
        }

        if (isset($url['restore']) && 'preview' == $routeType) {
            $this->adminBoutonService->addBtnRestore(
                $entity,
                [
                    'restore' => $url['restore'],
                    'list' => $url['trash']
                ],
                'Restore',
                [
                    'id' => $entity->getId(),
                ]
            );
        }

        if (isset($url['destroy']) && 'preview' == $routeType) {
            $this->adminBoutonService->addBtnDestroy(
                $entity,
                [
                    'destroy' => $url['destroy'],
                    'list' => $url['trash']
                ],
                'Destroy',
                [
                    'id' => $entity->getId(),
                ]
            );
        }

        if (isset($url['delete']) && 'show' == $routeType) {
            $urlsDelete = [
                'delete' => $url['delete'],
            ];
            if (isset($url['list'])) {
                $urlsDelete['list'] = $url['list'];
            }

            $this->adminBoutonService->addBtnDelete(
                $entity,
                $urlsDelete,
                'Supprimer',
                [
                    'id' => $entity->getId(),
                ]
            );
        }

        return $this->controller->render(
            $twigShow,
            ['entity' => $entity]
        );
    }

    public function create(
        object $entity,
        string $formType,
        array $url = [],
        array $events = [],
        object $manager = null
    ): Response
    {
        if (isset($url['list'])) {
            $this->adminBoutonService->addBtnList(
                $url['list']
            );
        }

        $oldEntity = clone $entity;
        $form      = $this->formFactory->create($formType, $entity);
        $this->adminBoutonService->addBtnSave($form->getName(), 'Ajouter');
        $form->handleRequest($this->request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!is_null($manager)) {
                $manager->setArrayCollection($entity);
            }

            $this->entityManager->persist($entity);
            $this->entityManager->flush();
            if (count($events) != 0) {
                foreach ($events as $event) {
                    $this->dispatcher->dispatch(
                        new $event($oldEntity, $entity)
                    );
                }
            }

            if (isset($url['list'])) {
                return new RedirectResponse(
                    $this->router->generate($url['list'])
                );
            }
        }

        return $this->controller->render(
            'admin/crud/form.html.twig',
            [
                'entity' => $entity,
                'form'   => $form->createView(),
            ]
        );
    }

    public function update(
        string $formType,
        object $entity,
        array $url = [],
        array $events = [],
        object $manager = null,
        string $twig = 'admin/crud/form.html.twig'
    ): Response
    {
        $this->setBtnViewUpdate($url, $entity);
        $oldEntity = clone $entity;
        $form      = $this->formFactory->create($formType, $entity);
        $this->adminBoutonService->addBtnSave($form->getName(), 'Sauvegarder');
        $form->handleRequest($this->request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!is_null($manager)) {
                $manager->setArrayCollection($entity);
            }

            $this->entityManager->flush();
            /** @var Session $session */
            $session = $this->session;
            $session->getFlashBag()->add(
                'success',
                'Données sauvegardé'
            );
            if (count($events) != 0) {
                foreach ($events as $event) {
                    $this->dispatcher->dispatch(
                        new $event($oldEntity, $entity)
                    );
                }
            }

            if (isset($url['list'])) {
                return new RedirectResponse(
                    $this->router->generate($url['list'])
                );
            }
        }

        return $this->controller->render(
            $twig,
            [
                'entity' => $entity,
                'form'   => $form->createView(),
            ]
        );
    }

    public function restore(ServiceEntityRepositoryLib $repository, string $id): JsonResponse
    {
        return new JsonResponse(
            []
        );
    }

    public function entityDeleteDestroyRestore(object $entity): JsonResponse
    {
        /**
         * TODO: refaire pour prendre en compte si c'est, suivant l'url, :
         *  - DELETE
         *  - DESTROY
         *  - RESTORE
         */
        return new JsonResponse(
            []
        );
        $state     = false;
        $token     = $this->request->request->get('_token');
        $csrfToken = new CsrfToken('delete' . $entity->getId(), $token);
        if ($this->csrfTokenManager->isTokenValid($csrfToken)) {
            $this->entityManager->remove($entity);
            $this->entityManager->flush();
            $state = true;
        }

        return new JsonResponse(
            [
                'state' => $state,
                'token' => $token,
                'all'   => $this->request->query->all(),
            ]
        );
    }

    /**
     * TODO: vide la corbeille
     */
    public function empty(ServiceEntityRepositoryLib $repository): JsonResponse
    {
        return new JsonResponse(
            []
        );
    }
}
