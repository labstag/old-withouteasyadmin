<?php

namespace Labstag\Lib;

use Labstag\Reader\UploadAnnotationReader;
use Labstag\Service\BlockService;
use Labstag\Service\OauthService;
use Labstag\Service\ParagraphService;
use Labstag\Service\PhoneService;
use Labstag\Service\RepositoryService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class FormTypeLib extends AbstractType
{
    public function __construct(
        protected BlockService $blockService,
        protected OauthService $oauthService,
        protected ParagraphService $paragraphService,
        protected PhoneService $phoneService,
        protected TranslatorInterface $translator,
        protected RepositoryService $repositoryService,
        protected UploadAnnotationReader $uploadAnnotationReader,
        protected RouterInterface $router
    )
    {
    }
}
