<?php

namespace Labstag\Service;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Labstag\Service\AdminBoutonService;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

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

    private $controller;

    public function __construct(
        AdminBoutonService $adminBoutonService,
        PaginatorInterface $paginator,
        RequestStack $requestStack,
        FormFactoryInterface $formFactory,
        RouterInterface $router,
        CsrfTokenManagerInterface $csrfTokenManager,
        EntityManagerInterface $entityManager
    )
    {
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

    public function setController($controller)
    {
        $this->controller = $controller;
    }

    public function list(
        $repository,
        $method,
        string $html,
        array $url = []
    )
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
        return $this->controller->render(
            $html,
            ['pagination' => $pagination]
        );
    }

    public function read(
        $entity,
        $twigShow,
        array $url = []
    )
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
            $this->adminBoutonService->addBtnDelete(
                $entity,
                $url['delete'],
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
        $entity,
        $formType,
        array $url = []
    )
    {
        if (isset($url['list'])) {
            $this->adminBoutonService->addBtnList(
                $url['list'],
                'Liste',
            );
        }

        $form = $this->formFactory->create($formType, $entity);
        $this->adminBoutonService->addBtnSave('Ajouter');
        $form->handleRequest($this->request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($entity);
            $this->entityManager->flush();

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
        $formType,
        $entity,
        array $url = []
    )
    {
        if (isset($url['list'])) {
            $this->adminBoutonService->addBtnList(
                $url['list'],
                'Liste',
            );
        }

        if (isset($url['delete'])) {
            $this->adminBoutonService->addBtnDelete(
                $entity,
                $url['delete'],
                'Supprimer',
                [
                    'id' => $entity->getId(),
                ]
            );
        }

        $form = $this->formFactory->create($formType, $entity);
        $this->adminBoutonService->addBtnSave('Sauvegarder');
        $form->handleRequest($this->request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

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

    public function delete($entity, string $url)
    {
        $token     = $this->request->request->get('_token');
        $csrfToken = new CsrfToken('delete'.$entity->getId(), $token);
        if ($this->csrfTokenManager->isTokenValid($csrfToken)) {
            $this->entityManager->remove($entity);
            $this->entityManager->flush();
        }

        return new RedirectResponse(
            $this->router->generate($url)
        );
    }

    public function trash()
    {

    }
}
