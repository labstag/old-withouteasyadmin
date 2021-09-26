<?php

namespace Labstag\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Labstag\Repository\BookmarkRepository;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

/**
 * @ORM\Entity(repositoryClass=BookmarkRepository::class)
 */
class Bookmark
{
    use SoftDeleteableEntity;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid", unique=true)
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Attachment::class, inversedBy="bookmarks")
     */
    private $img;

    /**
     * @ORM\ManyToMany(targetEntity=Libelle::class, inversedBy="bookmarks")
     */
    private $libelles;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="array")
     */
    private $state = [];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="bookmarks")
     */
    private $refcategory;

    public function __construct()
    {
        $this->libelles = new ArrayCollection();
    }

    public function addLibelle(Libelle $libelle): self
    {
        if (!$this->libelles->contains($libelle)) {
            $this->libelles[] = $libelle;
        }

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getImg(): ?Attachment
    {
        return $this->img;
    }

    /**
     * @return Collection|Libelle[]
     */
    public function getLibelles(): Collection
    {
        return $this->libelles;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getState(): ?array
    {
        return $this->state;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function removeLibelle(Libelle $libelle): self
    {
        $this->libelles->removeElement($libelle);

        return $this;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function setImg(?Attachment $img): self
    {
        $this->img = $img;

        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function setState(array $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getRefcategory(): ?Category
    {
        return $this->refcategory;
    }

    public function setRefcategory(?Category $refcategory): self
    {
        $this->refcategory = $refcategory;

        return $this;
    }
}
