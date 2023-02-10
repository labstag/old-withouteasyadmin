<?php

namespace Labstag\Entity\Paragraph;

use Doctrine\ORM\Mapping as ORM;
use Labstag\Annotation\Uploadable;
use Labstag\Annotation\UploadableField;
use Labstag\Entity\Attachment;
use Labstag\Entity\Paragraph;
use Labstag\Repository\Paragraph\VideoRepository;

/**
 * @ORM\Table(name="paragraph_video")
 *
 * @ORM\Entity(repositoryClass=VideoRepository::class)
 *
 * @Uploadable
 */
class Video
{

    /**
     * @UploadableField(filename="image", path="paragraph/video/image", slug="title")
     */
    protected $file;

    /**
     * @ORM\Id
     *
     * @ORM\GeneratedValue
     *
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Attachment::class, inversedBy="paragraphVideos")
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity=Paragraph::class, inversedBy="videos")
     */
    private $paragraph;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $url;

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

    public function getUrl(): ?string
    {
        return $this->url;
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

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }
}
