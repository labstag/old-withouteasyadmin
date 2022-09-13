<?php

namespace Labstag\Entity\Traits\Paragraph;

use Doctrine\Common\Collections\Collection;
use Labstag\Entity\Paragraph\Bookmark as ParagraphBookmark;
use Labstag\Entity\Paragraph\Bookmark\Category as BookmarkCategory;
use Labstag\Entity\Paragraph\Bookmark\Libelle as BookmarkLibelle;
use Labstag\Entity\Paragraph\Bookmark\Liste as BookmarkList;

trait BookmarkEntity
{

    /**
     * @ORM\OneToMany(targetEntity=BookmarkCategory::class, mappedBy="paragraph", orphanRemoval=true)
     */
    private $bookmarkCategories;

    /**
     * @ORM\OneToMany(targetEntity=BookmarkLibelle::class, mappedBy="paragraph", orphanRemoval=true)
     */
    private $bookmarkLibelles;

    /**
     * @ORM\OneToMany(targetEntity=BookmarkList::class, mappedBy="paragraph", orphanRemoval=true)
     */
    private $bookmarkLists;

    /**
     * @ORM\OneToMany(targetEntity=ParagraphBookmark::class, mappedBy="paragraph", orphanRemoval=true)
     */
    private $bookmarks;

    public function addBookmark(ParagraphBookmark $bookmark): self
    {
        if (!$this->bookmarks->contains($bookmark)) {
            $this->bookmarks[] = $bookmark;
            $bookmark->setParagraph($this);
        }

        return $this;
    }

    public function addBookmarkCategory(BookmarkCategory $bookmarkCategory): self
    {
        if (!$this->bookmarkCategories->contains($bookmarkCategory)) {
            $this->bookmarkCategories[] = $bookmarkCategory;
            $bookmarkCategory->setParagraph($this);
        }

        return $this;
    }

    public function addBookmarkLibelle(BookmarkLibelle $bookmarkLibelle): self
    {
        if (!$this->bookmarkLibelles->contains($bookmarkLibelle)) {
            $this->bookmarkLibelles[] = $bookmarkLibelle;
            $bookmarkLibelle->setParagraph($this);
        }

        return $this;
    }

    public function addBookmarkList(BookmarkList $bookmarkList): self
    {
        if (!$this->bookmarkLists->contains($bookmarkList)) {
            $this->bookmarkLists[] = $bookmarkList;
            $bookmarkList->setParagraph($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, BookmarkCategory>
     */
    public function getBookmarkCategories(): Collection
    {
        return $this->bookmarkCategories;
    }

    /**
     * @return Collection<int, BookmarkLibelle>
     */
    public function getBookmarkLibelles(): Collection
    {
        return $this->bookmarkLibelles;
    }

    /**
     * @return Collection<int, BookmarkList>
     */
    public function getBookmarkLists(): Collection
    {
        return $this->bookmarkLists;
    }

    /**
     * @return Collection<int, Bookmark>
     */
    public function getBookmarks(): Collection
    {
        return $this->bookmarks;
    }

    public function removeBookmark(ParagraphBookmark $bookmark): self
    {
        if ($this->bookmarks->removeElement($bookmark)) {
            // set the owning side to null (unless already changed)
            if ($bookmark->getParagraph() === $this) {
                $bookmark->setParagraph(null);
            }
        }

        return $this;
    }

    public function removeBookmarkCategory(BookmarkCategory $bookmarkCategory): self
    {
        if ($this->bookmarkCategories->removeElement($bookmarkCategory)) {
            // set the owning side to null (unless already changed)
            if ($bookmarkCategory->getParagraph() === $this) {
                $bookmarkCategory->setParagraph(null);
            }
        }

        return $this;
    }

    public function removeBookmarkLibelle(BookmarkLibelle $bookmarkLibelle): self
    {
        if ($this->bookmarkLibelles->removeElement($bookmarkLibelle)) {
            // set the owning side to null (unless already changed)
            if ($bookmarkLibelle->getParagraph() === $this) {
                $bookmarkLibelle->setParagraph(null);
            }
        }

        return $this;
    }

    public function removeBookmarkList(BookmarkList $bookmarkList): self
    {
        if ($this->bookmarkLists->removeElement($bookmarkList)) {
            // set the owning side to null (unless already changed)
            if ($bookmarkList->getParagraph() === $this) {
                $bookmarkList->setParagraph(null);
            }
        }

        return $this;
    }
}
