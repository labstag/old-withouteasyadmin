<?php

namespace Labstag\EventSubscriber;

use Labstag\Entity\User;
use Labstag\Lib\EventSubscriberLib;
use Symfony\Component\Workflow\Event\Event;

class WorkflowSubscriber extends EventSubscriberLib
{
    /**
     * @return array<string, string>
     */
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

    public function onTransition(Event $event): void
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

    public function transitionDisable(Event $event): void
    {
        unset($event);
    }

    public function transitionPasswordLost(Event $event): void
    {
        /** @var User $entity */
        $entity = $event->getSubject();
        $this->userMailService->lostPassword($entity);
        $this->sessionService->flashBagAdd(
            'success',
            $this->translator->trans('user.workflow.sendpasswordlink')
        );
    }

    public function transitionSubmit(Event $event): void
    {
        /** @var User $entity */
        $entity = $event->getSubject();
        if (User::class == $entity::class) {
            $this->userMailService->newUser($entity);
            $this->sessionService->flashBagAdd(
                'success',
                $this->translator->trans('user.workflow.new')
            );
        }
    }
}
