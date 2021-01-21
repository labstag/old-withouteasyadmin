<?php

namespace Labstag\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;

class UserWorkflowSubscriber implements EventSubscriberInterface
{

    public function onTransition(Event $event)
    {
        $transition = $event->getTransition();
        $name       = $transition->getName();
        switch ($name) {
            case 'submit':
                $this->transitionSubmit($event);
                break;
            case 'validation':
                $this->transitionValidation($event);
                break;
            case 'passwordlost':
                $this->transitionPasswordLost($event);
                break;
            case 'changepassword':
                $this->transitionChangerPassword($event);
                break;
            case 'desactiver':
                $this->transitionDesactiver($event);
                break;
            case 'activer':
                $this->transitionActiver($event);
                break;
        }
    }

    public function transitionSubmit(Event $event)
    {
        unset($event);
        dump('submit');
    }

    public function transitionValidation(Event $event)
    {
        unset($event);
        dump('validation');
    }

    public function transitionPasswordLost(Event $event)
    {
        unset($event);
        dump('passwordlost');
    }

    public function transitionChangerPassword(Event $event)
    {
        unset($event);
        dump('changepassword');
    }

    public function transitionDesactiver(Event $event)
    {
        unset($event);
        dump('desactiver');
    }

    public function transitionActiver(Event $event)
    {
        unset($event);
        dump('activer');
    }

    public static function getSubscribedEvents()
    {
        return ['workflow.user.transition' => 'onTransition'];
    }
}
