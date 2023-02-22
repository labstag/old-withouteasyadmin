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
        if ($entity instanceof Attachment) {
            return $this->attachmentStateMachine;
        }

        if ($entity instanceof Bookmark) {
            return $this->bookmarkStateMachine;
        }

        if ($entity instanceof Edito) {
            return $this->editoStateMachine;
        }

        if ($entity instanceof Email) {
            return $this->emailStateMachine;
        }

        if ($entity instanceof History) {
            return $this->historyStateMachine;
        }

        if ($entity instanceof Memo) {
            return $this->memoStateMachine;
        }

        if ($entity instanceof Phone) {
            return $this->phoneStateMachine;
        }

        if ($entity instanceof Post) {
            return $this->postStateMachine;
        }

        if ($entity instanceof User) {
            return $this->userStateMachine;
        }
    }

    public function has($entity): bool
    {
        $status = $entity instanceof Attachment;
        $status = ($entity instanceof Bookmark) ? true : $status;
        $status = ($entity instanceof Edito) ? true : $status;
        $status = ($entity instanceof Email) ? true : $status;
        $status = ($entity instanceof History) ? true : $status;
        $status = ($entity instanceof Memo) ? true : $status;
        $status = ($entity instanceof Phone) ? true : $status;
        $status = ($entity instanceof Post) ? true : $status;

        return ($entity instanceof User) ? true : $status;
    }
}
