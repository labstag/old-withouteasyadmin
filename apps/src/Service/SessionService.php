<?php

namespace Labstag\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\FlashBagAwareSessionInterface;

class SessionService
{
    public function __construct(
        protected RequestStack $requestStack
    ) {
    }

    public function flashBagAdd(
        string $type,
        string $message
    ): void {
        $requestStack = $this->requestStack;
        /** @var Request $request */
        $request = $requestStack->getCurrentRequest();
        if (is_null($request)) {
            return;
        }

        /** @var FlashBagAwareSessionInterface $session */
        $session  = $requestStack->getSession();
        $flashbag = $session->getFlashBag();
        $flashbag->add($type, $message);
    }
}
