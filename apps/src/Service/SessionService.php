<?php

namespace Labstag\Service;

use Symfony\Component\HttpFoundation\RequestStack;

class SessionService
{
    public function __construct(
        protected RequestStack $requestStack
    )
    {
    }

    public function flashBagAdd(string $type, $message): void
    {
        $requestStack = $this->requestStack;
        $request = $requestStack->getCurrentRequest();
        if (is_null($request)) {
            return;
        }

        $session = $requestStack->getSession();
        $flashbag = $session->getFlashBag();
        $flashbag->add($type, $message);
    }
}
