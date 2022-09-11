<?php

namespace Labstag\Controller\Admin\Post;

use Labstag\Entity\Paragraph;
use Labstag\Entity\Post;
use Labstag\Lib\ParagraphControllerLib;
use Labstag\Repository\ParagraphRepository;
use Labstag\RequestHandler\ParagraphRequestHandler;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/post/paragraph')]
class ParagraphController extends ParagraphControllerLib
{
    #[Route(path: '/add/{id}', name: 'admin_post_paragraph_add')]
    public function add(
        ParagraphRequestHandler $handler,
        Post $post,
        ParagraphRepository $repository,
        Request $request
    ): RedirectResponse
    {
        $paragraph = new Paragraph();
        $old       = clone $paragraph;
        $paragraph->setPosition(count($post->getParagraphs()) + 1);
        $paragraph->setPost($post);
        $paragraph->settype($request->get('data'));
        $repository->add($paragraph);
        $handler->handle($old, $paragraph);

        return $this->redirectToRoute('admin_post_paragraph_list', ['id' => $post->getId()]);
    }

    #[Route(path: '/delete/{id}', name: 'admin_post_paragraph_delete')]
    public function delete(Paragraph $paragraph): Response
    {
        return $this->deleteParagraph(
            $paragraph,
            $paragraph->getPost(),
            'admin_post_edit'
        );
    }

    #[Route(path: '/list/{id}', name: 'admin_post_paragraph_list')]
    public function list(Post $post): Response
    {
        return $this->listTwig(
            'admin_post_paragraph_show',
            $post->getParagraphs(),
            'admin_post_paragraph_delete'
        );
    }

    #[Route(path: '/show/{id}', name: 'admin_post_paragraph_show')]
    public function show(Paragraph $paragraph, ParagraphRequestHandler $handler)
    {
        return parent::showTwig($paragraph, $handler);
    }
}
