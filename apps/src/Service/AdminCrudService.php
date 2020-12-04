<?php

namespace Labstag\Service;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Labstag\Lib\AdminControllerLib;
use Labstag\Lib\ServiceEntityRepositoryLib;
use Labstag\Service\AdminBoutonService;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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

        $this->twig->addGlobal(
            'modalDelete',
            true
        );
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

    public function list(
        ServiceEntityRepositoryLib $repository,
        string $method,
        string $html,
        array $url = [],
        array $actions = []
    ): Response
    {
        if (isset($url['new'])) {
            $this->adminBoutonService->addBtnNew(
                $url['new'],
                'Nouveau',
            );
        }

        $pagination = $this->paginator->paginate(
            $repository->$method(),
            $this->request->query->getInt('page', 1),
            10
        );

        if (isset($actions['delete'])) {
            $this->twig->addGlobal(
                'modalDelete',
                true
            );
        }

        return $this->controller->render(
            $html,
            [
                'pagination' => $pagination,
                'actions'    => $actions,
            ]
        );
    }

    public function read(
        object $entity,
        string $twigShow,
        array $url = []
    ): Response
    {
        if (isset($url['list'])) {
            $this->adminBoutonService->addBtnList(
                $url['list'],
                'Liste',
            );
        }

        if (isset($url['edit'])) {
            $this->adminBoutonService->addEdit(
                $url['edit'],
                'Editer',
                [
                    'id' => $entity->getId(),
                ]
            );
        }

        if (isset($url['delete'])) {
            $this->twig->addGlobal(
                'modalDelete',
                true
            );
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
                $url['list'],
                'Liste',
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
        object $manager = null
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
            'admin/crud/form.html.twig',
            [
                'entity' => $entity,
                'form'   => $form->createView(),
            ]
        );
    }

    public function delete(object $entity): JsonResponse
    {
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
}
