<?php

namespace Labstag\Controller\Admin\Post;

use Labstag\Entity\Paragraph;
use Labstag\Entity\Post;
use Labstag\Service\Admin\ParagraphService;
use Labstag\Service\AdminService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/post/paragraph', name: 'admin_post_paragraph_')]
class ParagraphController extends AbstractController
{
    public function __construct(
        protected AdminService $adminService
    )
    {
    }

    #[Route(path: '/add/{id}', name: 'add')]
    public function add(
        Post $post
    ): RedirectResponse
    {
        return $this->paragraph()->add($post);
    }

    #[Route(path: '/delete/{id}', name: 'delete')]
    public function delete(Paragraph $paragraph): Response
    {
        return $this->paragraph()->delete($paragraph);
    }

    #[Route(path: '/list/{id}', name: 'list')]
    public function list(Post $post): Response
    {
        return $this->paragraph()->list($post->getParagraphs());
    }

    #[Route(path: '/show/{id}', name: 'show')]
    public function show(
        Paragraph $paragraph
    ): Response
    {
        return $this->paragraph()->show($paragraph);
    }

    private function paragraph(): ParagraphService
    {
        $paragraph = $this->adminService->paragraph();
        $paragraph->setUrls(
            'admin_post_paragraph_list',
            'admin_post_edit',
            'admin_post_paragraph_show',
            'admin_post_paragraph_delete'
        );

        return $paragraph;
    }
}
