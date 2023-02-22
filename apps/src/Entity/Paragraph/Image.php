<?php

namespace Labstag\Entity\Paragraph;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Labstag\Annotation\Uploadable;
use Labstag\Annotation\UploadableField;
use Labstag\Entity\Attachment;
use Labstag\Entity\Paragraph;
use Labstag\Repository\Paragraph\ImageRepository;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

/**
 * @ORM\Table(name="paragraph_image")
 *
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 *
 * @Uploadable
 */
class Image
{

    /**
     * @UploadableField(filename="image", path="paragraph/image", slug="title")
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
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity=Attachment::class, inversedBy="paragraphImages", cascade={"persist"})
     *
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private ?Attachment $image = null;

    /**
     * @ORM\ManyToOne(targetEntity=Paragraph::class, inversedBy="images", cascade={"persist"})
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
