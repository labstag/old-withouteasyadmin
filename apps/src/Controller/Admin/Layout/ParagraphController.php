<?php

namespace Labstag\Controller\Admin\Layout;

use Exception;
use Labstag\Entity\Layout;
use Labstag\Entity\Paragraph;
use Labstag\Interfaces\PublicInterface;
use Labstag\Lib\ParagraphControllerLib;
use Labstag\RequestHandler\ParagraphRequestHandler;
use Labstag\Service\ParagraphService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/layout/paragraph', name: 'admin_layout_paragraph_')]
class ParagraphController extends ParagraphControllerLib
{
    #[Route(path: '/add/{id}', name: 'add')]
    public function add(
        ParagraphService $paragraphService,
        Layout $layout,
        Request $request
    ): RedirectResponse
    {
        $data = $request->get('data');
        if (!is_string($data)) {
            throw new Exception('data is not string');
        }

        $paragraphService->add($layout, $data);

        return $this->redirectToRoute('admin_layout_paragraph_list', ['id' => $layout->getId()]);
    }

    #[Route(path: '/delete/{id}', name: 'delete')]
    public function delete(Paragraph $paragraph): Response
    {
        $layout = $paragraph->getLayout();
        if (!$layout instanceof PublicInterface) {
            throw new Exception('layout is not public interface');
        }

        return $this->deleteParagraph(
            $paragraph,
            $layout,
            'admin_layout_edit'
        );
    }

    #[Route(path: '/list/{id}', name: 'list')]
    public function list(Layout $layout): Response
    {
        return $this->listTwig(
            'admin_layout_paragraph_show',
            $layout->getParagraphs(),
            'admin_layout_paragraph_delete'
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
