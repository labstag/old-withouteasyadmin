<?php

namespace Labstag\Controller;

use Labstag\Entity\History;
use Labstag\Lib\FrontControllerLib;
use Labstag\Service\TemplatePageService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/history")
 */
class HistoryController extends FrontControllerLib
{
    /**
     * @Route("/archive/{code}", name="history_archive")
     */
    public function archive(
        TemplatePageService $templatePageService,
        Request $request,
        string $code
    )
    {
        $className = 'Labstag\TemplatePage\HistoryTemplatePage';
        $method    = 'archive';

        $class = $templatePageService->getClass($className);
        $class->setRequest($request);

        return $class->{$method}($code);
    }

    /**
     * @Route("/category/{code}", name="history_category")
     *
     * @return void
     */
    public function category(
        TemplatePageService $templatePageService,
        Request $request,
        string $code,
    )
    {
        $className = 'Labstag\TemplatePage\HistoryTemplatePage';
        $method    = 'category';

        $class = $templatePageService->getClass($className);
        $class->setRequest($request);

        return $class->{$method}($code);
    }

    /**
     * @Route("/libelle/{code}", name="history_libelle")
     *
     * @return void
     */
    public function libelle(
        TemplatePageService $templatePageService,
        Request $request,
        string $code
    )
    {
        $className = 'Labstag\TemplatePage\HistoryTemplatePage';
        $method    = 'libelle';

        $class = $templatePageService->getClass($className);
        $class->setRequest($request);

        return $class->{$method}($code);
    }

    /**
     * @Route("/{slug}.pdf", name="history_pdf")
     */
    public function pdf(
        TemplatePageService $templatePageService,
        Request $request,
        History $history
    )
    {
        $className = 'Labstag\TemplatePage\HistoryTemplatePage';
        $method    = 'pdf';

        $class = $templatePageService->getClass($className);
        $class->setRequest($request);

        return $class->{$method}($history);
    }

    /**
     * @Route("/{slug}", name="history_show")
     */
    public function show(
        Request $request,
        TemplatePageService $templatePageService,
        History $history
    )
    {
        $className = 'Labstag\TemplatePage\HistoryTemplatePage';
        $method    = 'show';

        $class = $templatePageService->getClass($className);
        $class->setRequest($request);

        return $class->{$method}($history);
    }

    /**
     * @Route("/user/{username}", name="history_user")
     */
    public function user(
        TemplatePageService $templatePageService,
        Request $request,
        $username
    )
    {
        $className = 'Labstag\TemplatePage\HistoryTemplatePage';
        $method    = 'user';

        $class = $templatePageService->getClass($className);
        $class->setRequest($request);

        return $class->{$method}($username);
    }
}
