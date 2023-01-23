<?php

namespace Labstag\Entity;

use Doctrine\ORM\Mapping as ORM;
use Labstag\Repository\AddressUserRepository;

/**
 * @ORM\Entity(repositoryClass=AddressUserRepository::class)
 */
class AddressUser extends Address
{

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="addressUsers", cascade={"persist"})
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
