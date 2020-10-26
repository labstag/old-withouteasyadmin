<?php

namespace Labstag\Menu;


use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class AdminMenuBuilder
{

    private $factory;

    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createMainMenu(RequestStack $requestStack): ItemInterface
    {
        unset($requestStack);
        $menu = $this->factory->createItem('menulabstag');
        $menu->setChildrenAttribute('class', 'navbar-nav');

        $menu->addChild(
            'Home',
            ['route' => 'admin']
        );

        // // access services from the container!
        // $em = $this->container->get('doctrine')->getManager();
        // // findMostRecent and Blog are just imaginary examples
        // $blog = $em->getRepository(Blog::class)->findMostRecent();

        // $menu->addChild('Latest Blog Post', [
        //     'route' => 'blog_show',
        //     'routeParameters' => ['id' => $blog->getId()]
        // ]);

        // create another menu item
        $menu->addChild('About 3');
        $menu->addChild('About 1');
        // you can also add sub levels to your menus as follows
        $menu['About 1']->addChild('Edit 1', ['route' => 'adresse_user_index']);
        $menu['About 1']->addChild('Edit 2', ['route' => 'adresse_user_index']);
        $menu['About 1']->addChild('Edit 3', ['route' => 'adresse_user_index']);
        $menu['About 1']->addChild('Edit 4', ['route' => 'adresse_user_index']);
        $menu->addChild('About 2');
        // you can also add sub levels to your menus as follows
        $menu['About 2']->addChild('Edit 5', ['route' => 'adresse_user_index']);
        $menu['About 2']->addChild('Edit 6', ['route' => 'adresse_user_index']);
        $menu['About 2']->addChild('Edit 7', ['route' => 'adresse_user_index']);
        $menu['About 2']->addChild('Edit 8', ['route' => 'adresse_user_index']);

        // ... add more children

        return $menu;
    }
}
