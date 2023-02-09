<?php

namespace Labstag\Entity\Paragraph\Bookmark;

use Doctrine\ORM\Mapping as ORM;
use Labstag\Entity\Paragraph;
use Labstag\Repository\Paragraph\Bookmark\CategoryRepository;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

/**
 * @ORM\Table(name="paragraph_bookmark_category")
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 */
class Category
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\Column(type="guid", unique=true)
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Paragraph::class, inversedBy="bookmarkCategories")
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
