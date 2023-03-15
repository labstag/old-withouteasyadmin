<?php

namespace Labstag\Entity\Traits\Paragraph;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Labstag\Entity\Paragraph\Post\Archive as PostArchive;
use Labstag\Entity\Paragraph\Post as ParagraphPost;
use Labstag\Entity\Paragraph\Post\Category as PostCategory;
use Labstag\Entity\Paragraph\Post\Header as PostHeader;
use Labstag\Entity\Paragraph\Post\Libelle as PostLibelle;
use Labstag\Entity\Paragraph\Post\Liste as PostList;
use Labstag\Entity\Paragraph\Post\Show as PostShow;
use Labstag\Entity\Paragraph\Post\User as PostUser;
use Labstag\Entity\Paragraph\Post\Year as PostYear;
use Labstag\Entity\Post;

trait PostEntity
{

    #[ORM\ManyToOne(
        targetEntity: Post::class,
        inversedBy: 'paragraphs',
        cascade: ['persist']
    )
    ]
    private ?Post $post = null;

    #[ORM\OneToMany(
        targetEntity: PostArchive::class,
        mappedBy: 'paragraph',
        cascade: ['persist'],
        orphanRemoval: true
    )
    ]
    private Collection $postArchives;

    #[ORM\OneToMany(
        targetEntity: PostCategory::class,
        mappedBy: 'paragraph',
        cascade: ['persist'],
        orphanRemoval: true
    )
    ]
    private Collection $postCategories;

    #[ORM\OneToMany(
        targetEntity: PostHeader::class,
        mappedBy: 'paragraph',
        cascade: ['persist'],
        orphanRemoval: true
    )
    ]
    private Collection $postHeaders;

    #[ORM\OneToMany(
        targetEntity: PostLibelle::class,
        mappedBy: 'paragraph',
        cascade: ['persist'],
        orphanRemoval: true
    )
    ]
    private Collection $postLibelles;

    #[ORM\OneToMany(
        targetEntity: PostList::class,
        mappedBy: 'paragraph',
        cascade: ['persist'],
        orphanRemoval: true
    )
    ]
    private Collection $postLists;

    #[ORM\OneToMany(
        targetEntity: ParagraphPost::class,
        mappedBy: 'paragraph',
        cascade: ['persist'],
        orphanRemoval: true
    )
    ]
    private Collection $posts;

    #[ORM\OneToMany(
        targetEntity: PostShow::class,
        mappedBy: 'paragraph',
        cascade: ['persist'],
        orphanRemoval: true
    )
    ]
    private Collection $postShows;

    #[ORM\OneToMany(
        targetEntity: PostUser::class,
        mappedBy: 'paragraph',
        cascade: ['persist'],
        orphanRemoval: true
    )
    ]
    private Collection $postUsers;

    #[ORM\OneToMany(
        targetEntity: PostYear::class,
        mappedBy: 'paragraph',
        cascade: ['persist'],
        orphanRemoval: true
    )
    ]
    private Collection $postYears;

    public function addPost(ParagraphPost $paragraphPost): self
    {
        if (!$this->posts->contains($paragraphPost)) {
            $this->posts[] = $paragraphPost;
            $paragraphPost->setParagraph($this);
        }

        return $this;
    }

    public function addPostArchive(PostArchive $postArchive): self
    {
        if (!$this->postArchives->contains($postArchive)) {
            $this->postArchives[] = $postArchive;
            $postArchive->setParagraph($this);
        }

        return $this;
    }

    public function addPostCategory(PostCategory $postCategory): self
    {
        if (!$this->postCategories->contains($postCategory)) {
            $this->postCategories[] = $postCategory;
            $postCategory->setParagraph($this);
        }

        return $this;
    }

    public function addPostHeader(PostHeader $postHeader): self
    {
        if (!$this->postHeaders->contains($postHeader)) {
            $this->postHeaders[] = $postHeader;
            $postHeader->setParagraph($this);
        }

        return $this;
    }

    public function addPostLibelle(PostLibelle $postLibelle): self
    {
        if (!$this->postLibelles->contains($postLibelle)) {
            $this->postLibelles[] = $postLibelle;
            $postLibelle->setParagraph($this);
        }

        return $this;
    }

    public function addPostList(PostList $postList): self
    {
        if (!$this->postLists->contains($postList)) {
            $this->postLists[] = $postList;
            $postList->setParagraph($this);
        }

        return $this;
    }

    public function addPostShow(PostShow $postShow): self
    {
        if (!$this->postShows->contains($postShow)) {
            $this->postShows[] = $postShow;
            $postShow->setParagraph($this);
        }

        return $this;
    }

    public function addPostUser(PostUser $postUser): self
    {
        if (!$this->postUsers->contains($postUser)) {
            $this->postUsers[] = $postUser;
            $postUser->setParagraph($this);
        }

        return $this;
    }

    public function addPostYear(PostYear $postYear): self
    {
        if (!$this->postYears->contains($postYear)) {
            $this->postYears[] = $postYear;
            $postYear->setParagraph($this);
        }

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    /**
     * @return Collection<int, PostArchive>
     */
    public function getPostArchives(): Collection
    {
        return $this->postArchives;
    }

    /**
     * @return Collection<int, PostCategory>
     */
    public function getPostCategories(): Collection
    {
        return $this->postCategories;
    }

    /**
     * @return Collection<int, PostHeader>
     */
    public function getPostHeaders(): Collection
    {
        return $this->postHeaders;
    }

    /**
     * @return Collection<int, PostLibelle>
     */
    public function getPostLibelles(): Collection
    {
        return $this->postLibelles;
    }

    /**
     * @return Collection<int, PostList>
     */
    public function getPostLists(): Collection
    {
        return $this->postLists;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    /**
     * @return Collection<int, PostShow>
     */
    public function getPostShows(): Collection
    {
        return $this->postShows;
    }

    /**
     * @return Collection<int, PostUser>
     */
    public function getPostUsers(): Collection
    {
        return $this->postUsers;
    }

    /**
     * @return Collection<int, PostYear>
     */
    public function getPostYears(): Collection
    {
        return $this->postYears;
    }

    public function removePost(ParagraphPost $paragraphPost): self
    {
        $this->removeElementPost(
            element: $this->posts,
            paragraphPost: $paragraphPost
        );

        return $this;
    }

    public function removePostArchive(PostArchive $postArchive): self
    {
        $this->removeElementPost(
            element: $this->postArchives,
            postArchive: $postArchive
        );

        return $this;
    }

    public function removePostCategory(PostCategory $postCategory): self
    {
        $this->removeElementPost(
            element: $this->postCategories,
            postCategory: $postCategory
        );

        return $this;
    }

    public function removePostHeader(PostHeader $postHeader): self
    {
        $this->removeElementPost(
            element: $this->postHeaders,
            postHeader: $postHeader
        );

        return $this;
    }

    public function removePostLibelle(PostLibelle $postLibelle): self
    {
        $this->removeElementPost(
            element: $this->postLibelles,
            postLibelle: $postLibelle
        );

        return $this;
    }

    public function removePostList(PostList $postList): self
    {
        $this->removeElementPost(
            element: $this->postLists,
            postList: $postList
        );

        return $this;
    }

    public function removePostShow(PostShow $postShow): self
    {
        $this->removeElementPost(
            element: $this->postShows,
            postShow: $postShow
        );

        return $this;
    }

    public function removePostUser(PostUser $postUser): self
    {
        $this->removeElementPost(
            element: $this->postUsers,
            postUser: $postUser
        );

        return $this;
    }

    public function removePostYear(PostYear $postYear): self
    {
        $this->removeElementPost(
            element: $this->postYears,
            postYear: $postYear
        );

        return $this;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    private function removeElementPost(
        Collection $element,
        ?ParagraphPost $paragraphPost = null,
        ?PostArchive $postArchive = null,
        ?PostCategory $postCategory = null,
        ?PostHeader $postHeader = null,
        ?PostLibelle $postLibelle = null,
        ?PostList $postList = null,
        ?PostShow $postShow = null,
        ?PostUser $postUser = null,
        ?PostYear $postYear = null
    ): void
    {
        $variable = is_null($paragraphPost) ? null : $paragraphPost;
        $variable = is_null($postArchive) ? $variable : $postArchive;
        $variable = is_null($postCategory) ? $variable : $postCategory;
        $variable = is_null($postHeader) ? $variable : $postHeader;
        $variable = is_null($postLibelle) ? $variable : $postLibelle;
        $variable = is_null($postList) ? $variable : $postList;
        $variable = is_null($postShow) ? $variable : $postShow;
        $variable = is_null($postUser) ? $variable : $postUser;
        $variable = is_null($postYear) ? $variable : $postYear;

        if (!is_null($variable) && $element->removeElement($variable) && $variable->getParagraph() === $this) {
            $variable->setParagraph(null);
        }
    }
}
