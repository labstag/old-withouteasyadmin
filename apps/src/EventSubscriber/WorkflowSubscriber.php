<?php

namespace Labstag\EventSubscriber;

use Labstag\Entity\User;
use Labstag\Service\UserMailService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Contracts\Translation\TranslatorInterface;

class WorkflowSubscriber implements EventSubscriberInterface
{
    public function __construct(
        protected UserMailService $userMailService,
        protected RequestStack $requestStack,
        protected TranslatorInterface $translator
    )
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'workflow.attachment.transition' => 'onTransition',
            'workflow.bookmark.transition'   => 'onTransition',
            'workflow.chapter.transition'    => 'onTransition',
            'workflow.edito.transition'      => 'onTransition',
            'workflow.email.transition'      => 'onTransition',
            'workflow.history.transition'    => 'onTransition',
            'workflow.memo.transition'       => 'onTransition',
            'workflow.phone.transition'      => 'onTransition',
            'workflow.post.transition'       => 'onTransition',
            'workflow.user.transition'       => 'onTransition',
        ];
    }

    public function onTransition(Event $event)
    {
        $transition = $event->getTransition();
        $name       = $transition->getName();
        if ('submit' == $name) {
            $this->transitionSubmit($event);

            return;
        }

        if ('passwordlost' == $name) {
            $this->transitionPasswordLost($event);

            return;
        }

        $this->transitionDisable($event);
    }

    public function transitionDisable(Event $event)
    {
        unset($event);
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
        if (User::class == $entity::class) {
            $this->userMailService->newUser($entity);
            $this->flashBagAdd(
                'success',
                $this->translator->trans('user.workflow.new')
            );
        }
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
