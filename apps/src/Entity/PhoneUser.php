<?php

namespace Labstag\Entity;

use Doctrine\ORM\Mapping as ORM;
use Labstag\Repository\PhoneUserRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PhoneUserRepository::class)]
class PhoneUser extends Phone
{

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'phoneUsers', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'refuser_id')]
    #[Assert\NotBlank]
    private ?UserInterface $user = null;

    public function getRefuser(): ?UserInterface
    {
        return $this->user;
    }

    public function setRefuser(?UserInterface $user): self
    {
        $this->user = $user;

        return $this;
    }
}
