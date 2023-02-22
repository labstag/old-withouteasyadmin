<?php

namespace Labstag\Entity\Block;

use Doctrine\ORM\Mapping as ORM;
use Labstag\Entity\Block;
use Labstag\Repository\Block\BreadcrumbRepository;
use Stringable;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

/**
 * @ORM\Table(name="block_breadcrumb")
 *
 * @ORM\Entity(repositoryClass=BreadcrumbRepository::class)
 */
class Breadcrumb implements Stringable
{

    /**
     * @ORM\ManyToOne(targetEntity=Block::class, inversedBy="breadcrumbs", cascade={"persist"})
     */
    private ?Block $block = null;

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
