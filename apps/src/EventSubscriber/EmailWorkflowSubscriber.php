<?php

namespace Labstag\EventSubscriber;

use Labstag\Entity\EmailUser;
use Labstag\Service\UserMailService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;

class EmailWorkflowSubscriber implements EventSubscriberInterface
{
    public function __construct(protected UserMailService $userMailService)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return ['workflow.email.transition' => 'onTransition'];
    }

    public function onTransition(Event $event)
    {
        $transition = $event->getTransition();
        $name       = $transition->getName();
        switch ($name) {
            case 'submit':
                $this->transitionSubmit($event);

                break;
        }
    }

    public function transitionSubmit(Event $event)
    {
        // @var EmailUser $entity
        $entity = $event->getSubject();
        $user   = $entity->getRefuser();
        if ($entity->getAddress() == $user->getEmail()) {
            return;
        }

        $this->userMailService->checkNewMail($entity->getRefuser(), $entity);
    }
}
