<?php

namespace Labstag\Entity\Block;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Labstag\Entity\Block;
use Labstag\Entity\Menu;
use Labstag\Interfaces\BlockInterface;
use Labstag\Interfaces\EntityInterface;
use Labstag\Repository\Block\NavbarRepository;
use Stringable;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

#[ORM\Entity(repositoryClass: NavbarRepository::class)]
#[ORM\Table(name: 'block_navbar')]
#[ApiResource(routePrefix: '/block')]
class Navbar implements Stringable, BlockInterface, EntityInterface
{

    #[ORM\ManyToOne(targetEntity: Block::class, inversedBy: 'menu', cascade: ['persist'])]
    private ?Block $block = null;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'guid', unique: true)]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?string $id = null;

    #[ORM\ManyToOne(targetEntity: Menu::class, inversedBy: 'navbars', cascade: ['persist'])]
    private ?Menu $menu = null;

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

    public function getMenu(): ?Menu
    {
        return $this->menu;
    }

    public function setBlock(?Block $block): self
    {
        $this->block = $block;

        return $this;
    }

    public function setMenu(?Menu $menu): self
    {
        $this->menu = $menu;

        return $this;
    }
}
