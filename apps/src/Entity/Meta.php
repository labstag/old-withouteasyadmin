<?php

namespace Labstag\Entity;

use Doctrine\ORM\Mapping as ORM;
use Labstag\Repository\MetaRepository;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

/**
 * @ORM\Entity(repositoryClass=MetaRepository::class)
 */
class Meta
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\Column(type="guid", unique=true)
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity=Chapter::class, inversedBy="metas")
     */
    private $chapter;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=Edito::class, inversedBy="metas")
     */
    private $edito;

    /**
     * @ORM\ManyToOne(targetEntity=History::class, inversedBy="metas")
     */
    private $history;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $keywords;

    /**
     * @ORM\ManyToOne(targetEntity=Page::class, inversedBy="metas")
     */
    private $page;

    /**
     * @ORM\ManyToOne(targetEntity=Post::class, inversedBy="metas")
     */
    private $post;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    public function getChapter(): ?Chapter
    {
        return $this->chapter;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getEdito(): ?Edito
    {
        return $this->edito;
    }

    public function getHistory(): ?History
    {
        return $this->history;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getKeywords(): ?string
    {
        return $this->keywords;
    }

    public function getPage(): ?Page
    {
        return $this->page;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setChapter(?Chapter $chapter): self
    {
        $this->chapter = $chapter;

        return $this;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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

    public function setKeywords(?string $keywords): self
    {
        $this->keywords = $keywords;

        return $this;
    }

    public function setPage(?Page $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }
}
