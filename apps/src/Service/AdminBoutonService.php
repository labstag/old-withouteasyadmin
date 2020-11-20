<?php

namespace Labstag\Service;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class AdminBoutonService
{

    protected array $bouton;

    protected CsrfTokenManagerInterface $csrfTokenManager;

    protected RouterInterface $router;

    public function __construct(
        CsrfTokenManagerInterface $csrfTokenManager,
        RouterInterface $router
    )
    {
        $this->csrfTokenManager = $csrfTokenManager;
        $this->bouton           = [];
        $this->router           = $router;
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

    public function addBtnDelete(
        object $entity,
        string $route,
        string $text = 'Supprimer',
        array $routeParam = []
    ): self
    {
        $code  = 'delete'.$entity->getId();
        $token = $this->csrfTokenManager->getToken($code)->getValue();
        $attr  = [
            'id'         => 'DeleteForm',
            'data-token' => $token,
        ];
        if ($route != '') {
            $attr['data-url'] = $this->router->generate($route, $routeParam);
        }

        $this->add(
            'BtnAdminHeaderDelete',
            $text,
            $attr
        );

        return $this;
    }

    public function addBtnSave(string $text = 'Sauvegarder'): self
    {
        $this->add(
            'BtnAdminHeaderSave',
            $text,
            ['id' => 'SaveForm']
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
