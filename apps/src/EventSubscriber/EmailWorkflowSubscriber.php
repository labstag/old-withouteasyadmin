<?php

namespace Labstag\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;

class EmailWorkflowSubscriber implements EventSubscriberInterface
{

    public function onTransition(Event $event)
    {
        $transition = $event->getTransition();
        $name       = $transition->getName();
        switch ($name) {
            case 'submit':
                $this->transitionSubmit($event);
                break;
            case 'valider':
                $this->transitionValider($event);
                break;
        }
    }

    public function transitionSubmit(Event $event)
    {
        unset($event);
        dump('submit');
    }

    public function transitionValider(Event $event)
    {
        unset($event);
        dump('valider');
    }

    public static function getSubscribedEvents()
    {
        return ['workflow.email.transition' => 'onTransition'];
    }
}
