<?php

namespace Labstag\Entity\Paragraph;

use Doctrine\ORM\Mapping as ORM;
use Labstag\Entity\Paragraph;
use Labstag\Lib\EntityParagraphLib;
use Labstag\Repository\Paragraph\BookmarkRepository;
use Stringable;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

/**
 * @ORM\Table(name="paragraph_bookmark")
 *
 * @ORM\Entity(repositoryClass=BookmarkRepository::class)
 */
class Bookmark implements Stringable, EntityParagraphLib
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
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity=Paragraph::class, inversedBy="bookmarks", cascade={"persist"})
     */
    private ?Paragraph $paragraph = null;

    public function __toString(): string
    {
        return (string) $this->getParagraph()->getType();
    }

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
