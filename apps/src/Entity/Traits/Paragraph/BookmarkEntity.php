<?php

namespace Labstag\Entity\Traits\Paragraph;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Labstag\Entity\Paragraph\Bookmark as ParagraphBookmark;
use Labstag\Entity\Paragraph\Bookmark\Category as BookmarkCategory;
use Labstag\Entity\Paragraph\Bookmark\Libelle as BookmarkLibelle;
use Labstag\Entity\Paragraph\Bookmark\Liste as BookmarkList;

trait BookmarkEntity
{
    #[ORM\OneToMany(
        targetEntity: BookmarkCategory::class,
        mappedBy: 'paragraph',
        cascade: ['persist'],
        orphanRemoval: true
    )
    ]
    private Collection $bookmarkCategories;

    #[ORM\OneToMany(
        targetEntity: BookmarkLibelle::class,
        mappedBy: 'paragraph',
        cascade: ['persist'],
        orphanRemoval: true
    )
    ]
    private Collection $bookmarkLibelles;

    #[ORM\OneToMany(
        targetEntity: BookmarkList::class,
        mappedBy: 'paragraph',
        cascade: ['persist'],
        orphanRemoval: true
    )
    ]
    private Collection $bookmarkLists;

    #[ORM\OneToMany(
        targetEntity: ParagraphBookmark::class,
        mappedBy: 'paragraph',
        cascade: ['persist'],
        orphanRemoval: true
    )
    ]
    private Collection $bookmarks;

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

    public function getBookmarks(): Collection
    {
        return $this->bookmarks;
    }

    public function removeBookmark(ParagraphBookmark $paragraphBookmark): self
    {
        $this->removeElementBookmark(
            element: $this->bookmarks,
            paragraphBookmark: $paragraphBookmark
        );

        return $this;
    }

    public function removeBookmarkCategory(BookmarkCategory $bookmarkCategory): self
    {
        $this->removeElementBookmark(
            element: $this->bookmarkCategories,
            bookmarkCategory: $bookmarkCategory
        );

        return $this;
    }

    public function removeBookmarkLibelle(BookmarkLibelle $bookmarkLibelle): self
    {
        $this->removeElementBookmark(
            element: $this->bookmarkLibelles,
            bookmarkLibelle: $bookmarkLibelle
        );

        return $this;
    }

    public function removeBookmarkList(BookmarkList $bookmarkList): self
    {
        // set the owning side to null (unless already changed)
        $this->removeElementBookmark(
            element: $this->bookmarkLists,
            bookmarkList: $bookmarkList
        );

        return $this;
    }

    private function removeElementBookmark(
        Collection $element,
        ?ParagraphBookmark $paragraphBookmark = null,
        ?BookmarkCategory $bookmarkCategory = null,
        ?BookmarkLibelle $bookmarkLibelle = null,
        ?BookmarkList $bookmarkList = null
    ): void {
        $variable = is_null($paragraphBookmark) ? null : $paragraphBookmark;
        $variable = is_null($bookmarkCategory) ? $variable : $bookmarkCategory;
        $variable = is_null($bookmarkLibelle) ? $variable : $bookmarkLibelle;
        $variable = is_null($bookmarkList) ? $variable : $bookmarkList;
        if (!is_null($variable) && $element->removeElement($variable) && $variable->getParagraph() === $this) {
            $variable->setParagraph(null);
        }
    }
}
