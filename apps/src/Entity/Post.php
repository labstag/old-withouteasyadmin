<?php

namespace Labstag\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Labstag\Annotation\Uploadable;
use Labstag\Annotation\UploadableField;
use Labstag\Entity\Traits\StateableEntity;
use Labstag\Repository\PostRepository;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @Uploadable()
 */
class Post
{
    use SoftDeleteableEntity;

    use StateableEntity;

    /**
     * @UploadableField(filename="img", path="post/img", slug="title")
     */
    protected $file;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @var DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid", unique=true)
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Attachment::class, inversedBy="posts")
     */
    private $img;

    /**
     * @ORM\ManyToMany(targetEntity=Libelle::class, mappedBy="posts", cascade={"persist"})
     */
    private $libelles;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $metaDescription;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $metaKeywords;

    /**
     * @ORM\Column(type="datetime")
     */
    private $published;

    /**
     * @ORM\ManyToOne(targetEntity=Category::class, inversedBy="posts")
     */
    private $refcategory;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="posts")
     * @Assert\NotBlank
     * @ORM\JoinColumn(nullable=false)
     */
    private $refuser;

    /**
     * @ORM\Column(type="boolean")
     */
    private $remark;

    /**
     * @Gedmo\Slug(updatable=false, fields={"title"})
     * @ORM\Column(type="string",   length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     */
    private $title;

    /**
     * @var DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated;

    public function __construct()
    {
        $this->libelles = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    public function addLibelle(Libelle $libelle): self
    {
        if (!$this->libelles->contains($libelle)) {
            $this->libelles[] = $libelle;
            $libelle->addPost($this);
        }

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getFile()
    {
        return $this->file;
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

    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }

    public function getMetaKeywords(): ?string
    {
        return $this->metaKeywords;
    }

    public function getPublished(): ?DateTimeInterface
    {
        return $this->published;
    }

    public function getRefcategory(): ?Category
    {
        return $this->refcategory;
    }

    public function getRefuser(): ?User
    {
        return $this->refuser;
    }

    public function getRemark(): ?bool
    {
        return $this->remark;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getUpdated()
    {
        return $this->updated;
    }

    public function removeLibelle(Libelle $libelle): self
    {
        if ($this->libelles->removeElement($libelle)) {
            $libelle->removePost($this);
        }

        return $this;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function setFile($file): self
    {
        $this->file = $file;

        return $this;
    }

    public function setImg(?Attachment $img): self
    {
        $this->img = $img;

        return $this;
    }

    public function setMetaDescription(?string $metaDescription): self
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    public function setMetaKeywords(?string $metaKeywords): self
    {
        $this->metaKeywords = $metaKeywords;

        return $this;
    }

    public function setPublished(DateTimeInterface $published): self
    {
        $this->published = $published;

        return $this;
    }

    public function setRefcategory(?Category $refcategory): self
    {
        $this->refcategory = $refcategory;

        return $this;
    }

    public function setRefuser(?User $refuser): self
    {
        $this->refuser = $refuser;

        return $this;
    }

    public function setRemark(bool $remark): self
    {
        $this->remark = $remark;

        return $this;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }
}
