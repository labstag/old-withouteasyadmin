<?php

namespace Labstag\Entity;

use Doctrine\ORM\Mapping as ORM;
use Labstag\Repository\RouteUserRepository;

/**
 * @ORM\Entity(repositoryClass=RouteUserRepository::class)
 */
class RouteUser
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
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="routes")
     */
    protected $refuser;

    /**
     * @ORM\ManyToOne(targetEntity=Route::class, inversedBy="users")
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

    public function getRefuser(): ?User
    {
        return $this->refuser;
    }

    public function setRefuser(?User $refuser): self
    {
        $this->refuser = $refuser;

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
