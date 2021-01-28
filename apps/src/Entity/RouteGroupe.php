<?php

namespace Labstag\Entity;

use Labstag\Repository\RouteGroupeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RouteGroupeRepository::class)
 */
class RouteGroupe
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $state;

    /**
     * @ORM\ManyToOne(targetEntity=Groupe::class, inversedBy="routes")
     */
    protected $refgroupe;

    /**
     * @ORM\ManyToOne(targetEntity=Route::class, inversedBy="groupes")
     */
    protected $refroute;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isState(): ?bool
    {
        return $this->state;
    }

    public function setState(bool $state): self
    {
        $this->state = $state;

        return $this;
    }

    public function getRefgroupe(): ?Groupe
    {
        return $this->refgroupe;
    }

    public function setRefgroupe(?Groupe $refgroupe): self
    {
        $this->refgroupe = $refgroupe;

        return $this;
    }

    public function getRefroute(): ?Route
    {
        return $this->refroute;
    }

    public function setRefroute(?Route $refroute): self
    {
        $this->refroute = $refroute;

        return $this;
    }
}
