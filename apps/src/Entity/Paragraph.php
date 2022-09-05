<?php

namespace Labstag\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Labstag\Entity\Paragraph\Bookmark as ParagraphBookmark;
use Labstag\Entity\Paragraph\Edito as ParagraphEdito;
use Labstag\Entity\Paragraph\History as ParagraphHistory;
use Labstag\Entity\Paragraph\Post as ParagraphPost;
use Labstag\Entity\Paragraph\Text;
use Labstag\Repository\ParagraphRepository;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

/**
 * @ORM\Entity(repositoryClass=ParagraphRepository::class)
 */
class Paragraph
{

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
     * @ORM\OneToMany(targetEntity=ParagraphBookmark::class, mappedBy="paragraph", orphanRemoval=true)
     */
    private $bookmarks;

    /**
     * @ORM\ManyToOne(targetEntity=Chapter::class, inversedBy="paragraphs")
     */
    private $chapter;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $color;

    /**
     * @ORM\ManyToOne(targetEntity=Edito::class, inversedBy="paragraphs")
     */
    private $edito;

    /**
     * @ORM\OneToMany(targetEntity=ParagraphEdito::class, mappedBy="paragraph", orphanRemoval=true)
     */
    private $editos;

    /**
     * @ORM\OneToMany(targetEntity=ParagraphHistory::class, mappedBy="paragraph", orphanRemoval=true)
     */
    private $histories;

    /**
     * @ORM\ManyToOne(targetEntity=History::class, inversedBy="paragraphs")
     */
    private $history;

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
     * @ORM\ManyToOne(targetEntity=Post::class, inversedBy="paragraphs")
     */
    private $post;

    /**
     * @ORM\OneToMany(targetEntity=ParagraphPost::class, mappedBy="paragraph", orphanRemoval=true)
     */
    private $posts;

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
        $this->position  = 0;
        $this->texts     = new ArrayCollection();
        $this->bookmarks = new ArrayCollection();
        $this->histories = new ArrayCollection();
        $this->editos    = new ArrayCollection();
        $this->posts     = new ArrayCollection();
    }

    public function addBookmark(ParagraphBookmark $bookmark): self
    {
        if (!$this->bookmarks->contains($bookmark)) {
            $this->bookmarks[] = $bookmark;
            $bookmark->setParagraph($this);
        }

        return $this;
    }

    public function addEdito(ParagraphEdito $edito): self
    {
        if (!$this->editos->contains($edito)) {
            $this->editos[] = $edito;
            $edito->setParagraph($this);
        }

        return $this;
    }

    public function addHistory(ParagraphHistory $history): self
    {
        if (!$this->histories->contains($history)) {
            $this->histories[] = $history;
            $history->setParagraph($this);
        }

        return $this;
    }

    public function addPost(ParagraphPost $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setParagraph($this);
        }

        return $this;
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

    /**
     * @return Collection<int, Bookmark>
     */
    public function getBookmarks(): Collection
    {
        return $this->bookmarks;
    }

    public function getChapter(): ?Chapter
    {
        return $this->chapter;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function getEdito(): ?Edito
    {
        return $this->edito;
    }

    /**
     * @return Collection<int, Edito>
     */
    public function getEditos(): Collection
    {
        return $this->editos;
    }

    /**
     * @return Collection<int, History>
     */
    public function getHistories(): Collection
    {
        return $this->histories;
    }

    public function getHistory(): ?History
    {
        return $this->history;
    }

    public function getId(): ?string
    {
        return $this->id;
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

    public function getPost(): ?Post
    {
        return $this->post;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
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

    public function removeBookmark(ParagraphBookmark $bookmark): self
    {
        if ($this->bookmarks->removeElement($bookmark)) {
            // set the owning side to null (unless already changed)
            if ($bookmark->getParagraph() === $this) {
                $bookmark->setParagraph(null);
            }
        }

        return $this;
    }

    public function removeEdito(ParagraphEdito $edito): self
    {
        if ($this->editos->removeElement($edito)) {
            // set the owning side to null (unless already changed)
            if ($edito->getParagraph() === $this) {
                $edito->setParagraph(null);
            }
        }

        return $this;
    }

    public function removeHistory(ParagraphHistory $history): self
    {
        if ($this->histories->removeElement($history)) {
            // set the owning side to null (unless already changed)
            if ($history->getParagraph() === $this) {
                $history->setParagraph(null);
            }
        }

        return $this;
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

    public function setEdito(?Edito $edito): self
    {
        $this->edito = $edito;

        return $this;
    }

    public function setHistory(?History $history): self
    {
        $this->history = $history;

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

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
