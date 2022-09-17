<?php

namespace Labstag\Entity;

use Doctrine\ORM\Mapping as ORM;
use Labstag\Repository\PhoneUserRepository;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PhoneUserRepository::class)
 */
class PhoneUser extends Phone
{

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="phoneUsers")
     * @Assert\NotBlank
     */
    protected $refuser;

    public function getRefuser(): ?User
    {
        return $this->refuser;
    }

    public function setRefuser(?User $user): self
    {
        $this->refuser = $user;

        return $this;
    }
}
