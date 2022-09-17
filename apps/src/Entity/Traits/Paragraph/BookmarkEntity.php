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

    public function addBookmark(ParagraphBookmark $paragraphBookmark): self
    {
        if (!$this->bookmarks->contains($paragraphBookmark)) {
            $this->bookmarks[] = $paragraphBookmark;
            $paragraphBookmark->setParagraph($this);
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

    public function removeBookmark(ParagraphBookmark $paragraphBookmark): self
    {
        // set the owning side to null (unless already changed)
        if ($this->bookmarks->removeElement($paragraphBookmark) && $paragraphBookmark->getParagraph() === $this) {
            $paragraphBookmark->setParagraph(null);
        }

        return $this;
    }

    public function removeBookmarkCategory(BookmarkCategory $bookmarkCategory): self
    {
        // set the owning side to null (unless already changed)
        if ($this->bookmarkCategories->removeElement($bookmarkCategory) && $bookmarkCategory->getParagraph() === $this) {
            $bookmarkCategory->setParagraph(null);
        }

        return $this;
    }

    public function removeBookmarkLibelle(BookmarkLibelle $bookmarkLibelle): self
    {
        // set the owning side to null (unless already changed)
        if ($this->bookmarkLibelles->removeElement($bookmarkLibelle) && $bookmarkLibelle->getParagraph() === $this) {
            $bookmarkLibelle->setParagraph(null);
        }

        return $this;
    }

    public function removeBookmarkList(BookmarkList $bookmarkList): self
    {
        // set the owning side to null (unless already changed)
        if ($this->bookmarkLists->removeElement($bookmarkList) && $bookmarkList->getParagraph() === $this) {
            $bookmarkList->setParagraph(null);
        }

        return $this;
    }
}
