<?php

namespace Labstag\Entity;

use Labstag\Repository\AdresseUserRepository;
use Doctrine\ORM\Mapping as ORM;

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
