<?php

namespace Labstag\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Labstag\Annotation\Uploadable;
use Labstag\Annotation\UploadableField;
use Labstag\Repository\PostRepository;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @Uploadable()
 */
class Post
{
    use SoftDeleteableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid", unique=true)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @Gedmo\Slug(updatable=false, fields={"title"})
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity=Attachment::class, inversedBy="posts")
     */
    private $img;

    /**
     * @UploadableField(filename="img", path="post/img")
     */
    protected $file;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="posts")
     */
    private $refuser;

    /**
     * @ORM\Column(type="boolean")
     */
    private $commentaire;

    /**
     * @ORM\ManyToMany(targetEntity=Libelle::class, mappedBy="posts")
     */
    private $libelles;

    /**
     * @ORM\Column(type="array")
     */
    protected $state;

    /**
     * @var \DateTime $stateChanged
     *
     * @ORM\Column(name="state_changed", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="change", field={"state"})
     */
    private $stateChanged;

    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var \DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated;

    public function __construct()
    {
        $this->libelles = new ArrayCollection();
    }

    public function getState()
    {
        return $this->state;
    }

    public function setState($state)
    {
        $this->state = $state;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getImg(): ?Attachment
    {
        return $this->img;
    }

    public function setImg(?Attachment $img): self
    {
        $this->img = $img;

        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getRefuser(): ?User
    {
        return $this->refuser;
    }

    public function setRefuser(?User $refuser): self
    {
        $this->refuser = $refuser;

        return $this;
    }

    public function getCommentaire(): ?bool
    {
        return $this->commentaire;
    }

    public function setCommentaire(bool $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * @return Collection|Libelle[]
     */
    public function getLibelles(): Collection
    {
        return $this->libelles;
    }

    public function addLibelle(Libelle $libelle): self
    {
        if (!$this->libelles->contains($libelle)) {
            $this->libelles[] = $libelle;
            $libelle->addPost($this);
        }

        return $this;
    }

    public function removeLibelle(Libelle $libelle): self
    {
        if ($this->libelles->removeElement($libelle)) {
            $libelle->removePost($this);
        }

        return $this;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getUpdated()
    {
        return $this->updated;
    }

    public function getStateChanged()
    {
        return $this->stateChanged;
    }
}
