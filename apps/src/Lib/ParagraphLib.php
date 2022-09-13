<?php

namespace Labstag\Lib;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Labstag\Service\FormService;
use Labstag\Service\ParagraphService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

abstract class ParagraphLib extends AbstractController
{

    protected Request $request;

    public function __construct(
        protected PaginatorInterface $paginator,
        protected TranslatorInterface $translator,
        protected MailerInterface $mailer,
        protected Environment $twig,
        protected ParagraphService $paragraphService,
        protected RequestStack $requestStack,
        protected FormService $formService,
        protected EntityManagerInterface $entityManager
    )
    {
        $request       = $requestStack->getCurrentRequest();
        $this->request = $request;
    }

    protected function getParagraphFile(string $type)
    {
        $folder   = __DIR__.'/../../templates/';
        $htmltwig = '.html.twig';
        $files    = [
            'paragraph/'.$type.$htmltwig,
            'paragraph/default'.$htmltwig,
        ];

        $view = end($files);

        foreach ($files as $file) {
            if (is_file($folder.$file)) {
                $view = $file;

                break;
            }
        }

        if ('dev' == $this->getParameter('kernel.debug')) {
            dump(['paragraph', $type, $files, $view]);
        }

        return $view;
    }

    protected function getRepository(string $entity)
    {
        return $this->entityManager->getRepository($entity);
    }
}