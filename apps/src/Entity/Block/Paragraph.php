<?php

namespace Labstag\Entity\Block;

use Doctrine\ORM\Mapping as ORM;
use Labstag\Entity\Block;
use Labstag\Lib\EntityBlockLib;
use Labstag\Repository\Block\ParagraphRepository;
use Stringable;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

/**
 * @ORM\Table(name="block_paragraph")
 *
 * @ORM\Entity(repositoryClass=ParagraphRepository::class)
 */
class Paragraph implements Stringable, EntityBlockLib
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
     * @ORM\ManyToOne(targetEntity=Block::class, inversedBy="paragraphs", cascade={"persist"})
     */
    private ?Block $block = null;

    public function __toString(): string
    {
        return (string) $this->getBlock()->getTitle();
    }

    public function getBlock(): ?Block
    {
        return $this->block;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setBlock(?Block $block): self
    {
        $this->block = $block;

        return $this;
    }
}
