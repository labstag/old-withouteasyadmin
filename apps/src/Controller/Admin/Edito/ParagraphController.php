<?php

namespace Labstag\Controller\Admin\Edito;

use Exception;
use Labstag\Entity\Edito;
use Labstag\Entity\Paragraph;
use Labstag\Interfaces\PublicInterface;
use Labstag\Lib\ParagraphControllerLib;
use Labstag\RequestHandler\ParagraphRequestHandler;
use Labstag\Service\ParagraphService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/edito/paragraph', name: 'admin_edito_paragraph_')]
class ParagraphController extends ParagraphControllerLib
{
    #[Route(path: '/add/{id}', name: 'add')]
    public function add(
        ParagraphService $paragraphService,
        Edito $edito,
        Request $request
    ): RedirectResponse
    {
        $data = $request->get('data');
        if (!is_string($data)) {
            throw new Exception('data is not string');
        }

        $paragraphService->add($edito, $data);

        return $this->redirectToRoute('admin_edito_paragraph_list', ['id' => $edito->getId()]);
    }

    #[Route(path: '/delete/{id}', name: 'delete')]
    public function delete(Paragraph $paragraph): Response
    {
        $edito = $paragraph->getEdito();
        if (!$edito instanceof PublicInterface) {
            throw new Exception('edito is not public interface');
        }

        return $this->deleteParagraph(
            $paragraph,
            $edito,
            'admin_edito_edit'
        );
    }

    #[Route(path: '/list/{id}', name: 'list')]
    public function list(Edito $edito): Response
    {
        return $this->listTwig(
            'admin_edito_paragraph_show',
            $edito->getParagraphs(),
            'admin_edito_paragraph_delete'
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
