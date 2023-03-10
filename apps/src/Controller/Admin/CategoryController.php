<?php

namespace Labstag\Controller\Admin;

use Exception;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Category;
use Labstag\Lib\AdminControllerLib;
use Labstag\Lib\DomainLib;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/category')]
class CategoryController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_category_edit', methods: ['GET', 'POST'])]
    #[Route(path: '/new', name: 'admin_category_new', methods: ['GET', 'POST'])]
    public function edit(
        ?Category $category
    ): Response
    {
        return $this->form(
            $this->getDomainEntity(),
            is_null($category) ? new Category() : $category
        );
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/trash', name: 'admin_category_trash', methods: ['GET'])]
    #[Route(path: '/', name: 'admin_category_index', methods: ['GET'])]
    public function indexOrTrash(): Response
    {
        return $this->listOrTrash(
            $this->getDomainEntity(),
            'admin/category/index.html.twig'
        );
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/{id}', name: 'admin_category_show', methods: ['GET'])]
    #[Route(path: '/preview/{id}', name: 'admin_category_preview', methods: ['GET'])]
    public function showOrPreview(Category $category): Response
    {
        return $this->renderShowOrPreview(
            $this->getDomainEntity(),
            $category,
            'admin/category/show.html.twig'
        );
    }

    protected function getDomainEntity(): DomainLib
    {
        $domainLib = $this->domainService->getDomain(Category::class);
        if (!$domainLib instanceof DomainLib) {
            throw new Exception('Domain not found');
        }

        return $domainLib;
    }

    /**
     * @return array<string, string>
     */
    protected function getMethodsList(): array
    {
        return [
            'trash' => 'findTrashParentForAdmin',
            'all'   => 'findAllParentForAdmin',
        ];
    }
}
