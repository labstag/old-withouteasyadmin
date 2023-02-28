<?php

namespace Labstag\Entity;

use Doctrine\ORM\Mapping as ORM;
use Labstag\Repository\EmailUserRepository;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: EmailUserRepository::class)]
class EmailUser extends Email
{

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'emailUsers', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    private $refuser;

    public function getRefuser(): ?UserInterface
    {
        return $this->refuser;
    }

    public function setRefuser(?UserInterface $user): self
    {
        $this->refuser = $user;

        return $this;
    }
}
