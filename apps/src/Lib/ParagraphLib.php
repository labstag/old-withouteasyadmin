<?php

namespace Labstag\Lib;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Service\FormService;
use Labstag\Service\ParagraphService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Mailer\MailerInterface;
use Twig\Environment;

abstract class ParagraphLib extends AbstractController
{
    public function __construct(protected MailerInterface $mailer, protected Environment $twig, protected ParagraphService $paragraphService, protected RequestStack $requestStack, protected FormService $formService, protected EntityManagerInterface $entityManager)
    {
    }

    protected function getParagraphFile(string $type, $content)
    {
        $folder   = __DIR__.'/../../templates/';
        $htmltwig = '.html.twig';
        $slug     = $content->getSlug();
        $files    = [
            'paragraph/'.$slug.'-'.$type.$htmltwig,
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
