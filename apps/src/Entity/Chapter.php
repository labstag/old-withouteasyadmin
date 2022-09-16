<?php

namespace Labstag\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Labstag\Entity\Traits\StateableEntity;
use Labstag\Repository\ChapterRepository;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ChapterRepository::class)
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Chapter
{
    use SoftDeleteableEntity;
    use StateableEntity;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\Column(type="guid", unique=true)
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Meta::class, mappedBy="chapter", orphanRemoval=true)
     */
    private $metas;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private int $pages = 0;

    /**
     * @ORM\OneToMany(targetEntity=Paragraph::class, mappedBy="chapter", orphanRemoval=true)
     */
    private $paragraphs;

    /**
     * @ORM\Column(type="integer")
     */
    private $position;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $published;

    /**
     * @ORM\ManyToOne(targetEntity=History::class, inversedBy="chapters")
     * @Assert\NotBlank
     * @ORM\JoinColumn(nullable=false)
     */
    private $refhistory;

    /**
     * @Gedmo\Slug(updatable=false, fields={"name"})
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $updated;

    public function __construct()
    {
        $this->metas      = new ArrayCollection();
        $this->paragraphs = new ArrayCollection();
    }

    public function addMeta(Meta $meta): self
    {
        if (!$this->metas->contains($meta)) {
            $this->metas[] = $meta;
            $meta->setChapter($this);
        }

        return $this;
    }

    public function addParagraph(Paragraph $paragraph): self
    {
        if (!$this->paragraphs->contains($paragraph)) {
            $this->paragraphs[] = $paragraph;
            $paragraph->setChapter($this);
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getPages(): ?int
    {
        return $this->pages;
    }

    /**
     * @return Collection<int, Paragraph>
     */
    public function getParagraphs(): Collection
    {
        return $this->paragraphs;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function getPublished(): ?DateTimeInterface
    {
        return $this->published;
    }

    public function getRefhistory(): ?History
    {
        return $this->refhistory;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getUpdated(): ?DateTimeInterface
    {
        return $this->updated;
    }

    public function removeMeta(Meta $meta): self
    {
        if ($this->metas->removeElement($meta)) {
            // set the owning side to null (unless already changed)
            if ($meta->getChapter() === $this) {
                $meta->setChapter(null);
            }
        }

        return $this;
    }

    public function removeParagraph(Paragraph $paragraph): self
    {
        if ($this->paragraphs->removeElement($paragraph)) {
            // set the owning side to null (unless already changed)
            if ($paragraph->getChapter() === $this) {
                $paragraph->setChapter(null);
            }
        }

        return $this;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function setCreated(DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setPages(int $pages): self
    {
        $this->pages = $pages;

        return $this;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function setPublished(DateTimeInterface $published): self
    {
        $this->published = $published;

        return $this;
    }

    public function setRefhistory(?History $refhistory): self
    {
        $this->refhistory = $refhistory;

        return $this;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function setUpdated(DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }
}
