<?php

namespace Labstag\Service;

use Labstag\Entity\Attachment;
use Labstag\Entity\Bookmark;
use Labstag\Entity\Edito;
use Labstag\Entity\Email;
use Labstag\Entity\History;
use Labstag\Entity\Memo;
use Labstag\Entity\Phone;
use Labstag\Entity\Post;
use Labstag\Entity\User;
use Symfony\Component\Workflow\Workflow;
use Symfony\Component\Workflow\WorkflowInterface;

class WorkflowService
{
    public function __construct(
        protected WorkflowInterface $attachmentStateMachine,
        protected WorkflowInterface $bookmarkStateMachine,
        protected WorkflowInterface $editoStateMachine,
        protected WorkflowInterface $emailStateMachine,
        protected WorkflowInterface $historyStateMachine,
        protected WorkflowInterface $memoStateMachine,
        protected WorkflowInterface $phoneStateMachine,
        protected WorkflowInterface $postStateMachine,
        protected WorkflowInterface $userStateMachine
    )
    {
    }

    public function get($entity): Workflow
    {
        return match (true) {
            ($entity instanceof Attachment) => $this->attachmentStateMachine,
            ($entity instanceof Bookmark) => $this->bookmarkStateMachine,
            ($entity instanceof Edito) => $this->editoStateMachine,
            ($entity instanceof Email) => $this->emailStateMachine,
            ($entity instanceof History) => $this->historyStateMachine,
            ($entity instanceof Memo) => $this->memoStateMachine,
            ($entity instanceof Phone) => $this->phoneStateMachine,
            ($entity instanceof Post) => $this->postStateMachine,
            ($entity instanceof User) => $this->userStateMachine,
        };
    }

    public function has($entity): bool
    {
        $class = get_class($entity);
        $tabs = [
            Attachment::class,
            Bookmark::class,
            Edito::class,
            Email::class,
            History::class,
            Memo::class,
            Phone::class,
            Post::class,
            User::class,
        ];
        return in_array($class, $tabs);
    }
}
