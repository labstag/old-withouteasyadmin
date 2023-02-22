<?php

namespace Labstag\Entity\Paragraph\History;

use Doctrine\ORM\Mapping as ORM;
use Labstag\Entity\Paragraph;
use Labstag\Repository\Paragraph\History\ChapterRepository;
use Stringable;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

/**
 * @ORM\Table(name="paragraph_history_chapter")
 *
 * @ORM\Entity(repositoryClass=ChapterRepository::class)
 */
class Chapter implements Stringable
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
     * @ORM\ManyToOne(targetEntity=Paragraph::class, inversedBy="historyChapters", cascade={"persist"})
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
