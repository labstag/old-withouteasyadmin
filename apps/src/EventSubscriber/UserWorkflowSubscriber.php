<?php

namespace Labstag\EventSubscriber;

use Labstag\Service\UserMailService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\HttpFoundation\RequestStack;

class UserWorkflowSubscriber implements EventSubscriberInterface
{

    protected FlashBagInterface $flashbag;

    protected SessionInterface $session;

    protected UserMailService $userMailService;

    protected RequestStack $requestStack;

    public function __construct(
        UserMailService $userMailService,
        RequestStack $requestStack
    )
    {
        $this->requestStack    = $requestStack;
        $this->userMailService = $userMailService;
    }

    public static function getSubscribedEvents()
    {
        return ['workflow.user.transition' => 'onTransition'];
    }

    public function onTransition(Event $event)
    {
        $transition = $event->getTransition();
        $name       = $transition->getName();
        switch ($name) {
            case 'submit':
                $this->transitionSubmit($event);
                break;
            case 'passwordlost':
                $this->transitionPasswordLost($event);
                break;
        }
    }

    public function transitionPasswordLost(Event $event)
    {
        $entity = $event->getSubject();
        $this->userMailService->lostPassword($entity);
        $this->flashBagAdd(
            'success',
            'Demande de nouveau mot de passe envoyé'
        );
    }

    private function flashBagAdd(string $type, $message)
    {
        $requestStack = $this->requestStack;
        $request      = $requestStack->getCurrentRequest();
        if (is_null($request)) {
            return;
        }

        $session  = $requestStack->getSession();
        $flashbag = $session->getFlashBag();
        $flashbag->add($type, $message);
    }

    public function transitionSubmit(Event $event)
    {
        $entity = $event->getSubject();
        $this->userMailService->newUser($entity);
        $this->flashBagAdd(
            'success',
            'Nouveau compte utilisateur créer'
        );
    }
}
