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
        $this->removeElementPost($this->posts, $paragraphPost);

        return $this;
    }

    public function removePostArchive(PostArchive $postArchive): self
    {
        $this->removeElementPost($this->postArchives, $postArchive);

        return $this;
    }

    public function removePostCategory(PostCategory $postCategory): self
    {
        $this->removeElementPost($this->postCategories, $postCategory);

        return $this;
    }

    public function removePostHeader(PostHeader $postHeader): self
    {
        $this->removeElementPost($this->postHeaders, $postHeader);

        return $this;
    }

    public function removePostLibelle(PostLibelle $postLibelle): self
    {
        $this->removeElementPost($this->postLibelles, $postLibelle);

        return $this;
    }

    public function removePostList(PostList $postList): self
    {
        $this->removeElementPost($this->postLists, $postList);

        return $this;
    }

    public function removePostShow(PostShow $postShow): self
    {
        $this->removeElementPost($this->postShows, $postShow);

        return $this;
    }

    public function removePostUser(PostUser $postUser): self
    {
        $this->removeElementPost($this->postUsers, $postUser);

        return $this;
    }

    public function removePostYear(PostYear $postYear): self
    {
        $this->removeElementPost($this->postYears, $postYear);

        return $this;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    private function removeElementPost($element, $variable)
    {
        if ($element->removeElement($variable) && $variable->getParagraph() === $this) {
            $variable->setParagraph(null);
        }
    }
}
