<?php

namespace Labstag\Service;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class AdminBoutonService
{

    private CsrfTokenManagerInterface $csrfTokenManager;

    private RouterInterface $router;

    private array $bouton;

    public function __construct(
        CsrfTokenManagerInterface $csrfTokenManager,
        RouterInterface $router
    )
    {
        $this->csrfTokenManager = $csrfTokenManager;
        $this->router           = $router;
        $this->bouton           = [];
    }

    private function add(
        string $icon,
        string $text,
        array $attr = []
    ): self
    {
        if (!isset($attr['href'])) {
            $attr['href'] = '#';
        }

        $attr = array_merge(
            $attr,
            ['title' => $text]
        );

        $this->bouton[] = [
            'icon' => $icon,
            'text' => $text,
            'attr' => $attr,
        ];

        return $this;
    }

    public function addEdit(
        string $route,
        string $text = 'Editer',
        array $routeParam = []
    ): self
    {
        $attr = [];
        if ($route != '') {
            $attr['href'] = $this->router->generate($route, $routeParam);
        }

        $this->add(
            'BtnAdminHeaderEdit',
            $text,
            $attr
        );

        return $this;
    }

    public function addBtnShow(
        string $route,
        string $text = 'Show',
        array $routeParam = []
    ): self
    {
        $this->add(
            'BtnAdminHeaderShow',
            $text,
            [
                'href' => $this->router->generate($route, $routeParam),
            ]
        );

        return $this;
    }

    public function addBtnDelete(
        object $entity,
        array $route,
        string $text = 'Supprimer',
        array $routeParam = []
    ): self
    {
        $code  = 'delete' . $entity->getId();
        $token = $this->csrfTokenManager->getToken($code)->getValue();
        $attr  = [
            'id'          => 'DeleteForm',
            'data-token'  => $token,
            'data-toggle' => 'modal',
            'data-target' => '#deleteModal',
        ];
        if (isset($route['list'])) {
            $attr['data-redirect'] = $this->router->generate($route['list']);
        }

        if (isset($route['delete'])) {
            $attr['data-url'] = $this->router->generate(
                $route['delete'],
                $routeParam
            );
        }

        $this->add(
            'BtnAdminHeaderDelete',
            $text,
            $attr
        );

        return $this;
    }

    public function addBtnSave(string $form, string $text = 'Sauvegarder'): self
    {
        $this->add(
            'BtnAdminHeaderSave',
            $text,
            [
                'id'        => 'SaveForm',
                'data-form' => $form,
            ]
        );

        return $this;
    }

    public function addBtnNew(string $route, string $text = 'Nouveau'): self
    {
        $this->add(
            'BtnAdminHeaderNew',
            $text,
            [
                'href' => $this->router->generate($route),
            ]
        );

        return $this;
    }

    public function addBtnList(string $route, string $text = 'Liste'): self
    {
        $this->add(
            'BtnAdminHeaderList',
            $text,
            [
                'href' => $this->router->generate($route),
            ]
        );

        return $this;
    }

    public function get(): array
    {
        return $this->bouton;
    }
}
