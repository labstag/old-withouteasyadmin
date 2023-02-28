<?php

namespace Labstag\Entity;

use Labstag\Repository\OauthConnectUserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: OauthConnectUserRepository::class)]
class OauthConnectUser
{

    #[ORM\Column(type: 'array')]
    protected array $data = [];

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'guid', unique: true)]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    protected string $identity;

    #[ORM\Column(type: 'string', length: 255)]
    protected string $name;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'oauthConnectUsers', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    protected UserInterface $refuser;

    public function getData(): ?array
    {
        return $this->data;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getIdentity(): ?string
    {
        return $this->identity;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getRefuser(): ?UserInterface
    {
        return $this->refuser;
    }

    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function setIdentity(string $identity): self
    {
        $this->identity = $identity;

        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setRefuser(?UserInterface $user): self
    {
        $this->refuser = $user;

        return $this;
    }
}
