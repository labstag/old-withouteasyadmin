<?php

namespace Labstag\Entity\Traits\Paragraph;

use Doctrine\Common\Collections\Collection;
use Labstag\Entity\History;
use Labstag\Entity\Paragraph\History as ParagraphHistory;
use Labstag\Entity\Paragraph\History\Chapter as HistoryChapter;
use Labstag\Entity\Paragraph\History\Liste as HistoryList;
use Labstag\Entity\Paragraph\History\Show as HistoryShow;
use Labstag\Entity\Paragraph\History\User as HistoryUser;

trait HistoryEntity
{

    /**
     * @ORM\OneToMany(targetEntity=ParagraphHistory::class, mappedBy="paragraph", orphanRemoval=true)
     */
    private $histories;

    /**
     * @ORM\ManyToOne(targetEntity=History::class, inversedBy="paragraphs")
     */
    private $history;

    /**
     * @ORM\OneToMany(targetEntity=HistoryChapter::class, mappedBy="paragraph", orphanRemoval=true)
     */
    private $historyChapters;

    /**
     * @ORM\OneToMany(targetEntity=HistoryList::class, mappedBy="paragraph", orphanRemoval=true)
     */
    private $historyLists;

    /**
     * @ORM\OneToMany(targetEntity=HistoryShow::class, mappedBy="paragraph")
     */
    private $historyShows;

    /**
     * @ORM\OneToMany(targetEntity=HistoryUser::class, mappedBy="paragraph", orphanRemoval=true)
     */
    private $historyUsers;

    public function addHistory(ParagraphHistory $history): self
    {
        if (!$this->histories->contains($history)) {
            $this->histories[] = $history;
            $history->setParagraph($this);
        }

        return $this;
    }

    public function addHistoryChapter(HistoryChapter $historyChapter): self
    {
        if (!$this->historyChapters->contains($historyChapter)) {
            $this->historyChapters[] = $historyChapter;
            $historyChapter->setParagraph($this);
        }

        return $this;
    }

    public function addHistoryList(HistoryList $historyList): self
    {
        if (!$this->historyLists->contains($historyList)) {
            $this->historyLists[] = $historyList;
            $historyList->setParagraph($this);
        }

        return $this;
    }

    public function addHistoryShow(HistoryShow $historyShow): self
    {
        if (!$this->historyShows->contains($historyShow)) {
            $this->historyShows[] = $historyShow;
            $historyShow->setParagraph($this);
        }

        return $this;
    }

    public function addHistoryUser(HistoryUser $historyUser): self
    {
        if (!$this->historyUsers->contains($historyUser)) {
            $this->historyUsers[] = $historyUser;
            $historyUser->setParagraph($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, History>
     */
    public function getHistories(): Collection
    {
        return $this->histories;
    }

    public function getHistory(): ?History
    {
        return $this->history;
    }

    /**
     * @return Collection<int, HistoryChapter>
     */
    public function getHistoryChapters(): Collection
    {
        return $this->historyChapters;
    }

    /**
     * @return Collection<int, HistoryList>
     */
    public function getHistoryLists(): Collection
    {
        return $this->historyLists;
    }

    /**
     * @return Collection<int, HistoryShow>
     */
    public function getHistoryShows(): Collection
    {
        return $this->historyShows;
    }

    /**
     * @return Collection<int, HistoryUser>
     */
    public function getHistoryUsers(): Collection
    {
        return $this->historyUsers;
    }

    public function removeHistory(ParagraphHistory $history): self
    {
        if ($this->histories->removeElement($history)) {
            // set the owning side to null (unless already changed)
            if ($history->getParagraph() === $this) {
                $history->setParagraph(null);
            }
        }

        return $this;
    }

    public function removeHistoryChapter(HistoryChapter $historyChapter): self
    {
        if ($this->historyChapters->removeElement($historyChapter)) {
            // set the owning side to null (unless already changed)
            if ($historyChapter->getParagraph() === $this) {
                $historyChapter->setParagraph(null);
            }
        }

        return $this;
    }

    public function removeHistoryList(HistoryList $historyList): self
    {
        if ($this->historyLists->removeElement($historyList)) {
            // set the owning side to null (unless already changed)
            if ($historyList->getParagraph() === $this) {
                $historyList->setParagraph(null);
            }
        }

        return $this;
    }

    public function removeHistoryShow(HistoryShow $historyShow): self
    {
        if ($this->historyShows->removeElement($historyShow)) {
            // set the owning side to null (unless already changed)
            if ($historyShow->getParagraph() === $this) {
                $historyShow->setParagraph(null);
            }
        }

        return $this;
    }

    public function removeHistoryUser(HistoryUser $historyUser): self
    {
        if ($this->historyUsers->removeElement($historyUser)) {
            // set the owning side to null (unless already changed)
            if ($historyUser->getParagraph() === $this) {
                $historyUser->setParagraph(null);
            }
        }

        return $this;
    }

    public function setHistory(?History $history): self
    {
        $this->history = $history;

        return $this;
    }
}