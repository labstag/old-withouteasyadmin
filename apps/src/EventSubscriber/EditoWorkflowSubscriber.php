<?php

namespace Labstag\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\Workflow\Event\GuardEvent;

class EditoWorkflowSubscriber implements EventSubscriberInterface
{
    public function onWorkflowEditoGuardSubmit(GuardEvent $event)
    {
        dump('onWorkflowEditoGuardSubmit', get_class_methods($event));
        // ...
    }

    public function onWorkflowEditoEnter(Event $event)
    {
        dump('onWorkflowEditoEnter', get_class_methods($event));
        // ...
    }

    public function onWorkflowEditoLeave(Event $event)
    {
        dump('onWorkflowEditoLeave', get_class_methods($event));
        // ...
    }

    public function onWorkflowEditoTransition(Event $event)
    {
        dump('onWorkflowEditoTransition', get_class_methods($event));
        // ...
    }

    public static function getSubscribedEvents()
    {
        return [
            'workflow.edito.guard.submit' => 'onWorkflowEditoGuardSubmit',
            'workflow.edito.transition'   => 'onWorkflowEditoTransition',
            'workflow.edito.enter'        => 'onWorkflowEditoEnter',
            'workflow.edito.leave'        => 'onWorkflowEditoLeave',
        ];
    }
}
