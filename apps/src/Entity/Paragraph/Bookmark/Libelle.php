<?php

namespace Labstag\Entity\Paragraph\Bookmark;

use Doctrine\ORM\Mapping as ORM;
use Labstag\Entity\Paragraph;
use Labstag\Repository\Paragraph\Bookmark\LibelleRepository;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

/**
 * @ORM\Table(name="paragraph_bookmark_libelle")
 * @ORM\Entity(repositoryClass=LibelleRepository::class)
 */
class Libelle
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\Column(type="guid", unique=true)
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Paragraph::class, inversedBy="bookmarkLibelles")
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
