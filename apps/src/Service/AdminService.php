<?php

namespace Labstag\Service;

use Exception;
use Labstag\Interfaces\DomainInterface;
use Labstag\Service\Admin\ViewService;
use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;

class AdminService
{
    /**
     * @var int
     */
    final public const STATUSRESPONSE = 200;

    protected ?DomainInterface $domain = null;

    public function __construct(
        protected RewindableGenerator $rewindableGenerator,
        protected ViewService $viewService
    )
    {
    }

    public function setDomain(string $entity): ViewService
    {
        $service = $this->viewService;
        foreach ($this->rewindableGenerator as $row) {
            if ($row->getType() == $entity) {
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
