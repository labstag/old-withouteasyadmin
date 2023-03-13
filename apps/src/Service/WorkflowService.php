<?php

namespace Labstag\Service;

use Labstag\Entity\Attachment;
use Labstag\Entity\Bookmark;
use Labstag\Entity\Chapter;
use Labstag\Entity\Edito;
use Labstag\Entity\Email;
use Labstag\Entity\History;
use Labstag\Entity\Memo;
use Labstag\Entity\Phone;
use Labstag\Entity\Post;
use Labstag\Entity\User;
use Labstag\Interfaces\EntityInterface;
use Symfony\Component\Workflow\WorkflowInterface;

class WorkflowService
{

    private array $data = [];

    public function __construct(
        protected WorkflowInterface $attachmentStateMachine,
        protected WorkflowInterface $chapterStateMachine,
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
        $this->data = [
            Attachment::class => $this->attachmentStateMachine,
            Bookmark::class   => $this->bookmarkStateMachine,
            Edito::class      => $this->editoStateMachine,
            Email::class      => $this->emailStateMachine,
            History::class    => $this->historyStateMachine,
            Chapter::class    => $this->chapterStateMachine,
            Memo::class       => $this->memoStateMachine,
            Phone::class      => $this->phoneStateMachine,
            Post::class       => $this->postStateMachine,
            User::class       => $this->userStateMachine,
        ];
    }

    public function get(EntityInterface $entity): ?WorkflowInterface
    {
        if (isset($this->data[$entity::class])) {
            return $this->data[$entity::class];
        }

        return null;
    }

    public function has(EntityInterface $entity): bool
    {
        return isset($this->data[$entity::class]);
    }
}
