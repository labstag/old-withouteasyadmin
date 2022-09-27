<?php

namespace Labstag\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Labstag\Entity\Traits\StateableEntity;
use Labstag\Repository\HistoryRepository;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=HistoryRepository::class)
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class History
{
    use SoftDeleteableEntity;
    use StateableEntity;

    /**
     * @ORM\OneToMany(targetEntity=Chapter::class, mappedBy="refhistory", orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $chapters;

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
     * @ORM\OneToMany(targetEntity=Meta::class, mappedBy="history", orphanRemoval=true)
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
     * @ORM\OneToMany(targetEntity=Paragraph::class, mappedBy="history", orphanRemoval=true)
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $paragraphs;

    /**
     * @ORM\Column(type="datetime")
     */
    private $published;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="histories")
     * @Assert\NotBlank
     * @ORM\JoinColumn(nullable=false)
     */
    private $refuser;

    /**
     * @Gedmo\Slug(updatable=false, fields={"name"})
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $summary;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated;

    public function __construct()
    {
        $this->chapters   = new ArrayCollection();
        $this->metas      = new ArrayCollection();
        $this->paragraphs = new ArrayCollection();
    }

    public function addChapter(Chapter $chapter): self
    {
        if (!$this->chapters->contains($chapter)) {
            $this->chapters[] = $chapter;
            $chapter->setRefhistory($this);
        }

        return $this;
    }

    public function addMeta(Meta $meta): self
    {
        if (!$this->metas->contains($meta)) {
            $this->metas[] = $meta;
            $meta->setHistory($this);
        }

        return $this;
    }

    public function addParagraph(Paragraph $paragraph): self
    {
        if (!$this->paragraphs->contains($paragraph)) {
            $this->paragraphs[] = $paragraph;
            $paragraph->setHistory($this);
        }

        return $this;
    }

    public function getChapters(): Collection
    {
        return $this->chapters;
    }

    public function getChaptersPublished(): Collection
    {
        $arrayCollection = new ArrayCollection();
        $collection      = $this->getChapters();
        foreach ($collection as $chapter) {
            $state     = in_array('publie', (array) $chapter->getState());
            $published = $chapter->getPublished() <= new DateTime();
            if ($state && $published) {
                $arrayCollection->add($chapter);
            }
        }

        return $arrayCollection;
    }

    public function getCreated(): ?DateTimeInterface
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

    public function getPublished(): ?DateTimeInterface
    {
        return $this->published;
    }

    public function getRefuser(): ?User
    {
        return $this->refuser;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function getUpdated(): ?DateTimeInterface
    {
        return $this->updated;
    }

    public function removeChapter(Chapter $chapter): self
    {
        // set the owning side to null (unless already changed)
        if ($this->chapters->removeElement($chapter) && $chapter->getRefhistory() === $this) {
            $chapter->setRefhistory(null);
        }

        return $this;
    }

    public function removeMeta(Meta $meta): self
    {
        // set the owning side to null (unless already changed)
        if ($this->metas->removeElement($meta) && $meta->getHistory() === $this) {
            $meta->setHistory(null);
        }

        return $this;
    }

    public function removeParagraph(Paragraph $paragraph): self
    {
        // set the owning side to null (unless already changed)
        if ($this->paragraphs->removeElement($paragraph) && $paragraph->getHistory() === $this) {
            $paragraph->setHistory(null);
        }

        return $this;
    }

    public function setCreated(DateTimeInterface $dateTime): self
    {
        $this->created = $dateTime;

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

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function setSummary(string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    public function setUpdated(DateTimeInterface $dateTime): self
    {
        $this->updated = $dateTime;

        return $this;
    }
}
