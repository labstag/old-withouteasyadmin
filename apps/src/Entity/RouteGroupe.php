<?php

namespace Labstag\Entity;

use Doctrine\ORM\Mapping as ORM;
use Labstag\Repository\RouteGroupeRepository;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

#[ORM\Entity(repositoryClass: RouteGroupeRepository::class)]
class RouteGroupe
{

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'guid', unique: true)]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private $id;

    #[ORM\ManyToOne(targetEntity: Groupe::class, inversedBy: 'routes', cascade: ['persist'])]
    private $refgroupe;

    #[ORM\ManyToOne(targetEntity: Route::class, inversedBy: 'groupes', cascade: ['persist'])]
    private $refroute;

    #[ORM\Column(type: 'boolean')]
    private $state;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getRefgroupe(): ?Groupe
    {
        return $this->refgroupe;
    }

    public function getRefroute(): ?Route
    {
        return $this->refroute;
    }

    public function getState(): ?bool
    {
        return $this->state;
    }

    public function isState(): ?bool
    {
        return $this->state;
    }

    public function setRefgroupe(?Groupe $groupe): self
    {
        $this->refgroupe = $groupe;

        return $this;
    }

    public function setRefroute(?Route $route): self
    {
        $this->refroute = $route;

        return $this;
    }

    public function setState(bool $state): self
    {
        $this->state = $state;

        return $this;
    }
}
