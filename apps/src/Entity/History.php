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
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class History
{
    use SoftDeleteableEntity;
    use StateableEntity;

    /**
     * @ORM\OneToMany(targetEntity=Chapter::class, mappedBy="refhistory", cascade={"persist"}, orphanRemoval=true)
     *
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $chapters;

    /**
     * @Gedmo\Timestampable(on="create")
     *
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $created = null;

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
     * @ORM\OneToMany(targetEntity=Meta::class, mappedBy="history", cascade={"persist"}, orphanRemoval=true)
     */
    private $metas;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name = null;

    /**
     * @ORM\Column(type="integer")
     */
    private int $pages = 0;

    /**
     * @ORM\OneToMany(targetEntity=Paragraph::class, mappedBy="history", cascade={"persist"}, orphanRemoval=true)
     *
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $paragraphs;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $published = null;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="histories", cascade={"persist"})
     *
     * @Assert\NotBlank
     *
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $refuser = null;

    /**
     * @Gedmo\Slug(updatable=false, fields={"name"})
     *
     * @ORM\Column(type="string", length=255)
     */
    private ?string $slug = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $summary = null;

    /**
     * @Gedmo\Timestampable(on="update")
     *
     * @ORM\Column(type="datetime")
     */
    private ?DateTimeInterface $updated = null;

    public function __construct()
    {
        $this->chapters = new ArrayCollection();
        $this->metas = new ArrayCollection();
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
        $chapters = $this->getChapters();
        foreach ($chapters as $chapter) {
            $state = in_array('publie', (array) $chapter->getState());
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
        $this->removeElementHistory($this->chapters, $chapter);

        return $this;
    }

    public function removeMeta(Meta $meta): self
    {
        $this->removeElementHistory($this->metas, $meta);

        return $this;
    }

    public function removeParagraph(Paragraph $paragraph): self
    {
        $this->removeElementHistory($this->paragraphs, $paragraph);

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

    private function removeElementHistory($element, $variable)
    {
        if ($element->removeElement($variable) && $variable->getHistory() === $this) {
            $variable->setHistory(null);
        }
    }
}
