<?php

namespace Labstag\Controller;

use Labstag\Entity\History;
use Labstag\Lib\FrontControllerLib;
use Labstag\Service\TemplatePageService;
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
        string $code
    )
    {
        $className = 'Labstag\TemplatePage\HistoryTemplatePage';
        $method    = 'archive';

        $class = $templatePageService->getClass($className);

        return $class->{$method}($code);
    }

    /**
     * @Route("/category/{code}", name="history_category")
     *
     * @return void
     */
    public function category(
        TemplatePageService $templatePageService,
        string $code,
    )
    {
        $className = 'Labstag\TemplatePage\HistoryTemplatePage';
        $method    = 'category';

        $class = $templatePageService->getClass($className);

        return $class->{$method}($code);
    }

    /**
     * @Route("/libelle/{code}", name="history_libelle")
     *
     * @return void
     */
    public function libelle(
        TemplatePageService $templatePageService,
        string $code
    )
    {
        $className = 'Labstag\TemplatePage\HistoryTemplatePage';
        $method    = 'libelle';

        $class = $templatePageService->getClass($className);

        return $class->{$method}($code);
    }

    /**
     * @Route("/{slug}.pdf", name="history_pdf")
     */
    public function pdf(
        TemplatePageService $templatePageService,
        History $history
    )
    {
        $className = 'Labstag\TemplatePage\HistoryTemplatePage';
        $method    = 'pdf';

        $class = $templatePageService->getClass($className);

        return $class->{$method}($history);
    }

    /**
     * @Route("/{slug}", name="history_show")
     */
    public function show(
        TemplatePageService $templatePageService,
        History $history
    )
    {
        $className = 'Labstag\TemplatePage\HistoryTemplatePage';
        $method    = 'show';

        $class = $templatePageService->getClass($className);

        return $class->{$method}($history);
    }

    /**
     * @Route("/user/{username}", name="history_user")
     */
    public function user(
        TemplatePageService $templatePageService,
        $username
    )
    {
        $className = 'Labstag\TemplatePage\HistoryTemplatePage';
        $method    = 'user';

        $class = $templatePageService->getClass($className);

        return $class->{$method}($username);
    }
}
