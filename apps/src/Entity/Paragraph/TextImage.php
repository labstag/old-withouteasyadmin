<?php

namespace Labstag\Entity\Paragraph;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Labstag\Annotation\Uploadable;
use Labstag\Annotation\UploadableField;
use Labstag\Entity\Attachment;
use Labstag\Entity\Paragraph;
use Labstag\Interfaces\ParagraphInterface;
use Labstag\Repository\Paragraph\TextImageRepository;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

/**
 * @ORM\Table(name="paragraph_textimage")
 *
 * @ORM\Entity(repositoryClass=TextImageRepository::class)
 *
 * @Uploadable
 */
class TextImage implements ParagraphInterface
{

    /**
     * @UploadableField(filename="image", path="paragraph/textimage", slug="title")
     */
    private $file;

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
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $content = null;

    /**
     * @ORM\ManyToOne(targetEntity=Attachment::class, inversedBy="paragraphTextImages", cascade={"persist"})
     *
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private ?Attachment $image = null;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private ?bool $leftimage = false;

    /**
     * @ORM\ManyToOne(targetEntity=Paragraph::class, inversedBy="textImages", cascade={"persist"})
     */
    private ?Paragraph $paragraph = null;

    /**
     * @Gedmo\Slug(updatable=false, fields={"title"})
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $slug = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $title = null;

    public function __construct()
    {
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getImage(): ?Attachment
    {
        return $this->image;
    }

    public function getLeftimage(): ?bool
    {
        return $this->leftimage;
    }

    public function getParagraph(): ?Paragraph
    {
        return $this->paragraph;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getTitle(): ?string
    {
        return $this->title;
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

    public function setImage(?Attachment $attachment): self
    {
        $this->image = $attachment;

        return $this;
    }

    public function setLeftimage(?bool $leftimage): self
    {
        $this->leftimage = $leftimage;

        return $this;
    }

    public function setParagraph(?Paragraph $paragraph): self
    {
        $this->paragraph = $paragraph;

        return $this;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }
}
