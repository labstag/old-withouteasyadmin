<?php

namespace Labstag\Entity\Paragraph;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Labstag\Annotation\Uploadable;
use Labstag\Annotation\UploadableField;
use Labstag\Entity\Attachment;
use Labstag\Entity\Paragraph;
use Labstag\Repository\Paragraph\TextImageRepository;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

/**
 * @ORM\Table(name="paragraph_textimage")
 *
 * @ORM\Entity(repositoryClass=TextImageRepository::class)
 *
 * @Uploadable
 */
class TextImage
{

    /**
     * @UploadableField(filename="image", path="paragraph/textimage", slug="title")
     */
    protected $file;

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
     * @ORM\ManyToOne(targetEntity=Attachment::class, inversedBy="paragraphTextImages", cascade={"persist"})
     *
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity=Paragraph::class, inversedBy="textImages", cascade={"persist"})
     */
    private $paragraph;

    /**
     * @Gedmo\Slug(updatable=false, fields={"title"})
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    public function getFile()
    {
        return $this->file;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?Attachment
    {
        return $this->image;
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
