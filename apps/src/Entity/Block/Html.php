<?php

namespace Labstag\Entity\Block;

use Doctrine\ORM\Mapping as ORM;
use Labstag\Entity\Block;
use Labstag\Lib\EntityBlockLib;
use Labstag\Repository\Block\HtmlRepository;
use Stringable;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

/**
 * @ORM\Table(name="block_html")
 *
 * @ORM\Entity(repositoryClass=HtmlRepository::class)
 */
class Html implements Stringable, EntityBlockLib
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
     * @ORM\ManyToOne(targetEntity=Block::class, inversedBy="htmls", cascade={"persist"})
     */
    private ?Block $block = null;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private ?string $content = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $title = null;

    public function __toString(): string
    {
        return (string) $this->getBlock()->getTitle();
    }

    public function getBlock(): ?Block
    {
        return $this->block;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setBlock(?Block $block): self
    {
        $this->block = $block;

        return $this;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }
}
