<?php

namespace Labstag\Controller\Admin\Post;

use Exception;
use Labstag\Entity\Paragraph;
use Labstag\Entity\Post;
use Labstag\Lib\ParagraphControllerLib;
use Labstag\RequestHandler\ParagraphRequestHandler;
use Labstag\Service\ParagraphService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/post/paragraph', name: 'admin_post_paragraph_')]
class ParagraphController extends ParagraphControllerLib
{
    #[Route(path: '/add/{id}', name: 'add')]
    public function add(
        ParagraphService $paragraphService,
        Post $post,
        Request $request
    ): RedirectResponse
    {
        $data = $request->get('data');
        if (!is_string($data)) {
            throw new Exception('data is not string');
        }

        $paragraphService->add($post, $data);

        return $this->redirectToRoute('admin_post_paragraph_list', ['id' => $post->getId()]);
    }

    #[Route(path: '/delete/{id}', name: 'delete')]
    public function delete(Paragraph $paragraph): Response
    {
        return $this->deleteParagraph(
            $paragraph,
            $paragraph->getPost(),
            'admin_post_edit'
        );
    }

    #[Route(path: '/list/{id}', name: 'list')]
    public function list(Post $post): Response
    {
        return $this->listTwig(
            'admin_post_paragraph_show',
            $post->getParagraphs(),
            'admin_post_paragraph_delete'
        );
    }

    #[Route(path: '/show/{id}', name: 'show')]
    public function show(
        Paragraph $paragraph,
        ParagraphRequestHandler $paragraphRequestHandler
    ): Response
    {
        return parent::showTwig($paragraph, $paragraphRequestHandler);
    }
}
