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
use Labstag\Repository\EditoRepository;
use Stringable;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EditoRepository::class)
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @Uploadable
 */
class Edito implements Stringable
{
    use SoftDeleteableEntity;
    use StateableEntity;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Assert\NotBlank
     */
    protected $content;

    /**
     * @UploadableField(filename="fond", path="edito/fond", slug="title")
     */
    protected $file;

    /**
     * @ORM\ManyToOne(targetEntity=Attachment::class, inversedBy="editos")
     */
    protected $fond;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\Column(type="guid", unique=true)
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="editos")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $refuser;

    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     * @Assert\NotBlank
     */
    protected $title;

    /**
     * @ORM\OneToMany(targetEntity=Meta::class, mappedBy="edito", cascade={"persist"}, orphanRemoval=true)
     */
    private $metas;

    /**
     * @ORM\OneToMany(targetEntity=Paragraph::class, mappedBy="edito", cascade={"persist"}, orphanRemoval=true)
     */
    private $paragraphs;

    /**
     * @ORM\Column(type="datetime")
     */
    private $published;

    public function __construct()
    {
        $this->paragraphs = new ArrayCollection();
        $this->metas      = new ArrayCollection();
    }

    public function __toString(): string
    {
        return (string) $this->getTitle();
    }

    public function addMeta(Meta $meta): self
    {
        if (!$this->metas->contains($meta)) {
            $this->metas[] = $meta;
            $meta->setEdito($this);
        }

        return $this;
    }

    public function addParagraph(Paragraph $paragraph): self
    {
        if (!$this->paragraphs->contains($paragraph)) {
            $this->paragraphs[] = $paragraph;
            $paragraph->setEdito($this);
        }

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function getFond(): ?Attachment
    {
        return $this->fond;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Meta>
     */
    public function getMetas(): Collection
    {
        return $this->metas;
    }

    /**
     * @return Collection<int, Paragraph>
     */
    public function getParagraphs(): Collection
    {
        return $this->paragraphs;
    }

    public function getPublished(): ?DateTimeInterface
    {
        return $this->published;
    }

    public function getRefuser(): ?User
    {
        return $this->refuser;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function removeMeta(Meta $meta): self
    {
        // set the owning side to null (unless already changed)
        if ($this->metas->removeElement($meta) && $meta->getEdito() === $this) {
            $meta->setEdito(null);
        }

        return $this;
    }

    public function removeParagraph(Paragraph $paragraph): self
    {
        // set the owning side to null (unless already changed)
        if ($this->paragraphs->removeElement($paragraph) && $paragraph->getEdito() === $this) {
            $paragraph->setEdito(null);
        }

        return $this;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function setFile($file): self
    {
        $this->file = $file;

        return $this;
    }

    public function setFond(?Attachment $attachment): self
    {
        $this->fond = $attachment;

        return $this;
    }

    public function setPublished(DateTimeInterface $dateTime): self
    {
        $this->published = $dateTime;

        return $this;
    }

    public function setRefuser(?User $user): self
    {
        $this->refuser = $user;

        return $this;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }
}
