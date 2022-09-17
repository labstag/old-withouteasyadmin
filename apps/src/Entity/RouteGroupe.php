<?php

namespace Labstag\Entity;

use Doctrine\ORM\Mapping as ORM;
use Labstag\Repository\RouteGroupeRepository;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

/**
 * @ORM\Entity(repositoryClass=RouteGroupeRepository::class)
 */
class RouteGroupe
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\Column(type="guid", unique=true)
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity=Groupe::class, inversedBy="routes")
     */
    protected $refgroupe;

    /**
     * @ORM\ManyToOne(targetEntity=Route::class, inversedBy="groupes")
     */
    protected $refroute;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $state;

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
