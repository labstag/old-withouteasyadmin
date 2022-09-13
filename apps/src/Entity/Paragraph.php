<?php

namespace Labstag\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Labstag\Entity\Paragraph\Text;
use Labstag\Entity\Traits\Paragraph\BookmarkEntity;
use Labstag\Entity\Traits\Paragraph\EditoEntity;
use Labstag\Entity\Traits\Paragraph\HistoryEntity;
use Labstag\Entity\Traits\Paragraph\PostEntity;
use Labstag\Repository\ParagraphRepository;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

/**
 * @ORM\Entity(repositoryClass=ParagraphRepository::class)
 */
class Paragraph
{
    use BookmarkEntity;
    use EditoEntity;
    use HistoryEntity;
    use PostEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\Column(type="guid", unique=true)
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $background;

    /**
     * @ORM\ManyToOne(targetEntity=Chapter::class, inversedBy="paragraphs")
     */
    private $chapter;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $color;

    /**
     * @ORM\ManyToOne(targetEntity=Layout::class, inversedBy="paragraphs")
     */
    private $layout;

    /**
     * @ORM\ManyToOne(targetEntity=Memo::class, inversedBy="paragraphs")
     */
    private $memo;

    /**
     * @ORM\ManyToOne(targetEntity=Page::class, inversedBy="paragraphs")
     */
    private $page;

    /**
     * @ORM\Column(type="integer")
     */
    private $position;

    /**
     * @ORM\OneToMany(targetEntity=Text::class, mappedBy="paragraph", orphanRemoval=true)
     */
    private $texts;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    public function __construct()
    {
        $this->position           = 0;
        $this->texts              = new ArrayCollection();
        $this->bookmarks          = new ArrayCollection();
        $this->histories          = new ArrayCollection();
        $this->editos             = new ArrayCollection();
        $this->posts              = new ArrayCollection();
        $this->postLists          = new ArrayCollection();
        $this->historyLists       = new ArrayCollection();
        $this->bookmarkLists      = new ArrayCollection();
        $this->postArchives       = new ArrayCollection();
        $this->postUsers          = new ArrayCollection();
        $this->postLibelles       = new ArrayCollection();
        $this->bookmarkLibelles   = new ArrayCollection();
        $this->bookmarkCategories = new ArrayCollection();
        $this->postYears          = new ArrayCollection();
        $this->postShows          = new ArrayCollection();
        $this->postCategories     = new ArrayCollection();
        $this->postHeaders        = new ArrayCollection();
        $this->historyUsers       = new ArrayCollection();
        $this->historyChapters    = new ArrayCollection();
        $this->historyShows       = new ArrayCollection();
        $this->editoShows         = new ArrayCollection();
        $this->editoHeaders       = new ArrayCollection();
    }

    public function addText(Text $text): self
    {
        if (!$this->texts->contains($text)) {
            $this->texts[] = $text;
            $text->setParagraph($this);
        }

        return $this;
    }

    public function getBackground(): ?string
    {
        return $this->background;
    }

    public function getChapter(): ?Chapter
    {
        return $this->chapter;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getLayout(): ?Layout
    {
        return $this->layout;
    }

    public function getMemo(): ?Memo
    {
        return $this->memo;
    }

    public function getPage(): ?Page
    {
        return $this->page;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    /**
     * @return Collection<int, Text>
     */
    public function getTexts(): Collection
    {
        return $this->texts;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function removeText(Text $text): self
    {
        if ($this->texts->removeElement($text)) {
            // set the owning side to null (unless already changed)
            if ($text->getParagraph() === $this) {
                $text->setParagraph(null);
            }
        }

        return $this;
    }

    public function setBackground(?string $background): self
    {
        $this->background = $background;

        return $this;
    }

    public function setChapter(?Chapter $chapter): self
    {
        $this->chapter = $chapter;

        return $this;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function setLayout(?Layout $layout): self
    {
        $this->layout = $layout;

        return $this;
    }

    public function setMemo(?Memo $memo): self
    {
        $this->memo = $memo;

        return $this;
    }

    public function setPage(?Page $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}