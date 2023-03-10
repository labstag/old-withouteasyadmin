<?php

namespace Labstag\Lib;

use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\Paragraph;
use Labstag\Interfaces\ParagraphInterface;
use Labstag\Reader\UploadAnnotationReader;
use Labstag\Service\ErrorService;
use Labstag\Service\FileService;
use Labstag\Service\FormService;
use Labstag\Service\ParagraphService;
use Labstag\Service\RepositoryService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

abstract class ParagraphLib extends AbstractController
{

    protected array $template = [];

    public function __construct(
        protected RepositoryService $repositoryService,
        protected FileService $fileService,
        protected UploadAnnotationReader $uploadAnnotationReader,
        protected ErrorService $errorService,
        protected PaginatorInterface $paginator,
        protected TranslatorInterface $translator,
        protected MailerInterface $mailer,
        protected Environment $twigEnvironment,
        protected ParagraphService $paragraphService,
        protected RequestStack $requestStack,
        protected FormService $formService,
        protected EntityManagerInterface $entityManager
    )
    {
    }

    public function getCode(ParagraphInterface $entityParagraphLib): string
    {
        unset($entityParagraphLib);

        return '';
    }

    public function setData(Paragraph $paragraph): void
    {
        unset($paragraph);
    }

    public function template(mixed $entity): array
    {
        return $this->showTemplateFile($this->getCode($entity));
    }

    protected function getTemplateData(string $type): array
    {
        if (isset($this->template[$type])) {
            return $this->template[$type];
        }

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

        $this->template[$type] = [
            'hook'  => 'paragraph',
            'type'  => $type,
            'files' => $files,
            'view'  => $view,
        ];

        return $this->template[$type];
    }

    protected function getTemplateFile(string $type): string
    {
        $data = $this->getTemplateData($type);

        return $data['view'];
    }

    protected function showTemplateFile(string $type): array
    {
        $data    = $this->getTemplateData($type);
        $globals = $this->twigEnvironment->getGlobals();
        if ('dev' == $globals['app']->getDebug()) {
            return $data;
        }

        return [];
    }
}
