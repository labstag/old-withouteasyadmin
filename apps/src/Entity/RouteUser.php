<?php

namespace Labstag\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Labstag\Repository\RouteUserRepository;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: RouteUserRepository::class)]
#[ApiResource]
class RouteUser
{

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'guid', unique: true)]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private $id;

    #[ORM\ManyToOne(targetEntity: Route::class, inversedBy: 'users', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'refroute_id')]
    private $route;

    #[ORM\Column(type: 'boolean')]
    private $state;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'routes', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'refuser_id')]
    private $user;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getRefroute(): ?Route
    {
        return $this->route;
    }

    public function getRefuser(): ?UserInterface
    {
        return $this->user;
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
        $this->route = $route;

        return $this;
    }

    public function setRefuser(?UserInterface $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function setState(bool $state): self
    {
        $this->state = $state;

        return $this;
    }
}
