<?php

namespace Labstag\Entity\Traits\Paragraph;

use Doctrine\Common\Collections\Collection;
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

    /**
     * @ORM\ManyToOne(targetEntity=Post::class, inversedBy="paragraphs")
     */
    private $post;

    /**
     * @ORM\OneToMany(targetEntity=PostArchive::class, mappedBy="paragraph", orphanRemoval=true)
     */
    private $postArchives;

    /**
     * @ORM\OneToMany(targetEntity=PostCategory::class, mappedBy="paragraph", orphanRemoval=true)
     */
    private $postCategories;

    /**
     * @ORM\OneToMany(targetEntity=PostHeader::class, mappedBy="paragraph", orphanRemoval=true)
     */
    private $postHeaders;

    /**
     * @ORM\OneToMany(targetEntity=PostLibelle::class, mappedBy="paragraph", orphanRemoval=true)
     */
    private $postLibelles;

    /**
     * @ORM\OneToMany(targetEntity=PostList::class, mappedBy="paragraph", orphanRemoval=true)
     */
    private $postLists;

    /**
     * @ORM\OneToMany(targetEntity=ParagraphPost::class, mappedBy="paragraph", orphanRemoval=true)
     */
    private $posts;

    /**
     * @ORM\OneToMany(targetEntity=PostShow::class, mappedBy="paragraph", orphanRemoval=true)
     */
    private $postShows;

    /**
     * @ORM\OneToMany(targetEntity=PostUser::class, mappedBy="paragraph", orphanRemoval=true)
     */
    private $postUsers;

    /**
     * @ORM\OneToMany(targetEntity=PostYear::class, mappedBy="paragraph", orphanRemoval=true)
     */
    private $postYears;

    public function addPost(ParagraphPost $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setParagraph($this);
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

    public function removePost(ParagraphPost $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getParagraph() === $this) {
                $post->setParagraph(null);
            }
        }

        return $this;
    }

    public function removePostArchive(PostArchive $postArchive): self
    {
        if ($this->postArchives->removeElement($postArchive)) {
            // set the owning side to null (unless already changed)
            if ($postArchive->getParagraph() === $this) {
                $postArchive->setParagraph(null);
            }
        }

        return $this;
    }

    public function removePostCategory(PostCategory $postCategory): self
    {
        if ($this->postCategories->removeElement($postCategory)) {
            // set the owning side to null (unless already changed)
            if ($postCategory->getParagraph() === $this) {
                $postCategory->setParagraph(null);
            }
        }

        return $this;
    }

    public function removePostHeader(PostHeader $postHeader): self
    {
        if ($this->postHeaders->removeElement($postHeader)) {
            // set the owning side to null (unless already changed)
            if ($postHeader->getParagraph() === $this) {
                $postHeader->setParagraph(null);
            }
        }

        return $this;
    }

    public function removePostLibelle(PostLibelle $postLibelle): self
    {
        if ($this->postLibelles->removeElement($postLibelle)) {
            // set the owning side to null (unless already changed)
            if ($postLibelle->getParagraph() === $this) {
                $postLibelle->setParagraph(null);
            }
        }

        return $this;
    }

    public function removePostList(PostList $postList): self
    {
        if ($this->postLists->removeElement($postList)) {
            // set the owning side to null (unless already changed)
            if ($postList->getParagraph() === $this) {
                $postList->setParagraph(null);
            }
        }

        return $this;
    }

    public function removePostShow(PostShow $postShow): self
    {
        if ($this->postShows->removeElement($postShow)) {
            // set the owning side to null (unless already changed)
            if ($postShow->getParagraph() === $this) {
                $postShow->setParagraph(null);
            }
        }

        return $this;
    }

    public function removePostUser(PostUser $postUser): self
    {
        if ($this->postUsers->removeElement($postUser)) {
            // set the owning side to null (unless already changed)
            if ($postUser->getParagraph() === $this) {
                $postUser->setParagraph(null);
            }
        }

        return $this;
    }

    public function removePostYear(PostYear $postYear): self
    {
        if ($this->postYears->removeElement($postYear)) {
            // set the owning side to null (unless already changed)
            if ($postYear->getParagraph() === $this) {
                $postYear->setParagraph(null);
            }
        }

        return $this;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }
}
