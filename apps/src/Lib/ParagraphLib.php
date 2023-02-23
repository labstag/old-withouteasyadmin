<?php

namespace Labstag\Lib;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Knp\Component\Pager\PaginatorInterface;
use Labstag\Entity\Paragraph;
use Labstag\Reader\UploadAnnotationReader;
use Labstag\Service\ErrorService;
use Labstag\Service\FileService;
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

    protected ?Request $request;

    protected array $template = [];

    public function __construct(
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
        $this->request = $requestStack->getCurrentRequest();
    }

    public function getCode(EntityParagraphLib $entityParagraphLib): string
    {
        unset($entityParagraphLib);

        return '';
    }

    public function setData(Paragraph $paragraph)
    {
        unset($paragraph);
    }

    public function template($entity)
    {
        return $this->showTemplateFile($this->getCode($entity));
    }

    protected function getRepository(string $entity): EntityRepository
    {
        return $this->entityManager->getRepository($entity);
    }

    protected function getTemplateData(string $type): array
    {
        if (isset($this->template[$type])) {
            return $this->template[$type];
        }

        $folder = __DIR__.'/../../templates/';
        $htmltwig = '.html.twig';
        $files = [
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
        $data = $this->getTemplateData($type);
        $globals = $this->twigEnvironment->getGlobals();
        if ('dev' == $globals['app']->getDebug()) {
            return $data;
        }

        return [];
    }
}
