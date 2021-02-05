<?php

namespace Labstag\Entity;

use Doctrine\ORM\Mapping as ORM;
use Labstag\Repository\AdresseUserRepository;

/**
 * @ORM\Entity(repositoryClass=AdresseUserRepository::class)
 */
class AdresseUser extends Adresse
{

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="adresseUsers")
     */
    protected $refuser;

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
