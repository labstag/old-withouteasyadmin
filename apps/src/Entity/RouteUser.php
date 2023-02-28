<?php

namespace Labstag\Entity;

use Doctrine\ORM\Mapping as ORM;
use Labstag\Repository\RouteUserRepository;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: RouteUserRepository::class)]
class RouteUser
{

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'guid', unique: true)]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private $id;

    #[ORM\ManyToOne(targetEntity: Route::class, inversedBy: 'users', cascade: ['persist'])]
    private $refroute;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'routes', cascade: ['persist'])]
    private $refuser;

    #[ORM\Column(type: 'boolean')]
    private $state;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getRefroute(): ?Route
    {
        return $this->refroute;
    }

    public function getRefuser(): ?UserInterface
    {
        return $this->refuser;
    }

    public function getState(): ?bool
    {
        return $this->state;
    }

    public function isState(): ?bool
    {
        return $this->state;
    }

    public function setRefroute(?Route $route): self
    {
        $this->refroute = $route;

        return $this;
    }

    public function setRefuser(?UserInterface $user): self
    {
        $this->refuser = $user;

        return $this;
    }

    public function setState(bool $state): self
    {
        $this->state = $state;

        return $this;
    }
}
