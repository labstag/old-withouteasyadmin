<?php

namespace Labstag\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Labstag\Repository\CategoryRepository;
use Stringable;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Category implements Stringable
{
    use SoftDeleteableEntity;

    /**
     * @ORM\OneToMany(targetEntity=Bookmark::class, mappedBy="refcategory", orphanRemoval=true)
     */
    private $bookmarks;

    /**
     * @ORM\OneToMany(targetEntity=Category::class, mappedBy="parent", cascade={"persist"}, orphanRemoval=true)
     */
    private $children;

    /**
     * @ORM\Id
     *
     * @ORM\GeneratedValue(strategy="CUSTOM")
     *
     * @ORM\Column(type="guid", unique=true)
     *
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="children", cascade={"persist"})
     *
     * @ORM\JoinColumn(
     *     name="parent_id",
     *     referencedColumnName="id",
     *     onDelete="SET NULL"
     * )
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="refcategory", orphanRemoval=true)
     */
    private $posts;

    /**
     * @Gedmo\Slug(updatable=false, fields={"name"})
     *
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->bookmarks = new ArrayCollection();
    }

    public function __toString(): string
    {
        $category = $this->getParent();
        $text = is_null($category) ? '' : $category.' - ';

        return $text.$this->getName();
    }

    public function addBookmark(Bookmark $bookmark): self
    {
        if (!$this->bookmarks->contains($bookmark)) {
            $this->bookmarks[] = $bookmark;
            $bookmark->setRefcategory($this);
        }

        return $this;
    }

    public function addChild(self $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setParent($this);
        }

        return $this;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setRefcategory($this);
        }

        return $this;
    }

    public function getBookmarks(): Collection
    {
        return $this->bookmarks;
    }

    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function removeBookmark(Bookmark $bookmark): self
    {
        $this->removeElementCategory($this->bookmarks, $bookmark);

        return $this;
    }

    public function removeChild(self $child): self
    {
        // set the owning side to null (unless already changed)
        if ($this->children->removeElement($child) && $child->getParent() === $this) {
            $child->setParent(null);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        $this->removeElementCategory($this->posts, $post);

        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    private function removeElementCategory($element, $variable)
    {
        if ($element->removeElement($variable) && $variable->getRefcategory() === $this) {
            $variable->setRefcategory(null);
        }
    }
}
