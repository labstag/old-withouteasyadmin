<?php

namespace Labstag\Service;

use Exception;
use Labstag\Interfaces\AdminEntityServiceInterface;
use Labstag\Interfaces\DomainInterface;
use Labstag\Service\Gestion\ParagraphService;
use Labstag\Service\Gestion\ViewService;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

class GestionService
{
    /**
     * @var int
     */
    final public const STATUSRESPONSE = 200;

    protected ?DomainInterface $domain = null;

    protected $rewindableGenerator;

    public function __construct(
        #[TaggedIterator('entitiesadminservice')]
        iterable $rewindableGenerator,
        protected ParagraphService $paragraphService,
        protected ViewService $viewService
    )
    {
        $this->rewindableGenerator = $rewindableGenerator;
    }

    public function paragraph(): ParagraphService
    {
        return $this->paragraphService;
    }

    public function setDomain(string $entity): ViewService
    {
        $service = $this->viewService;
        foreach ($this->rewindableGenerator as $row) {
            /** @var AdminEntityServiceInterface $row */
            if ($row->getType() === $entity) {
                $service = $row;

                break;
            }
        }

        if (!$service instanceof ViewService) {
            throw new Exception('Service not found');
        }

        $service->setDomain($entity);

        return $service;
    }
}
