<?php

namespace Labstag\EventSubscriber;

use Labstag\Service\UserMailService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserWorkflowSubscriber implements EventSubscriberInterface
{

    protected FlashBagInterface $flashbag;

    protected RequestStack $requestStack;

    protected SessionInterface $session;

    protected TranslatorInterface $translator;

    protected UserMailService $userMailService;

    public function __construct(
        UserMailService $userMailService,
        RequestStack $requestStack,
        TranslatorInterface $translator
    )
    {
        $this->translator      = $translator;
        $this->requestStack    = $requestStack;
        $this->userMailService = $userMailService;
    }

    public static function getSubscribedEvents(): array
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
            $this->translator->trans('user.workflow.sendpasswordlink')
        );
    }

    public function transitionSubmit(Event $event)
    {
        $entity = $event->getSubject();
        $this->userMailService->newUser($entity);
        $this->flashBagAdd(
            'success',
            $this->translator->trans('user.workflow.new')
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
}
