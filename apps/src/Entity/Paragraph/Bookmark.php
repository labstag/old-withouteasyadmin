<?php

namespace Labstag\Entity\Paragraph;

use Doctrine\ORM\Mapping as ORM;
use Labstag\Entity\Paragraph;
use Labstag\Repository\Paragraph\BookmarkRepository;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

/**
 * @ORM\Table(name="paragraph_bookmark")
 *
 * @ORM\Entity(repositoryClass=BookmarkRepository::class)
 */
class Bookmark
{

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
     * @ORM\ManyToOne(targetEntity=Paragraph::class, inversedBy="bookmarks")
     */
    private $paragraph;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getParagraph(): ?Paragraph
    {
        return $this->paragraph;
    }

    public function setParagraph(?Paragraph $paragraph): self
    {
        $this->paragraph = $paragraph;

        return $this;
    }
}
