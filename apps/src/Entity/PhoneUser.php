<?php

namespace Labstag\Entity;

use Labstag\Repository\PhoneUserRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PhoneUserRepository::class)
 */
class PhoneUser extends Phone
{

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="phoneUsers")
     */
    private $refuser;

    public function getRefuser(): ?User
    {
        return $this->refuser;
    }

    public function setRefuser(?User $refuser): self
    {
        $this->refuser = $refuser;

        return $this;
    }
}
