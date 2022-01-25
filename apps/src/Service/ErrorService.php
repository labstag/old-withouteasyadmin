<?php
namespace Labstag\Service;

use Psr\Log\LoggerInterface;

class ErrorService
{
    public function __construct(
        protected LoggerInterface $logger,
        protected SessionService $sessionService
    )
    {

    }

    public function set($exception)
    {
        $errorMsg = sprintf(
            'Exception : Erreur %s dans %s L.%s : %s',
            $exception->getCode(),
            $exception->getFile(),
            $exception->getLine(),
            $exception->getMessage()
        );
        $this->logger->error($errorMsg);
        $this->sessionService->flashBagAdd('danger', $errorMsg);
    }
}
