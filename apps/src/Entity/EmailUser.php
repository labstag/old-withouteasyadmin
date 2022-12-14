<?php

namespace Labstag\Entity;

use Doctrine\ORM\Mapping as ORM;
use Labstag\Repository\EmailUserRepository;

/**
 * @ORM\Entity(repositoryClass=EmailUserRepository::class)
 */
class EmailUser extends Email
{

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="emailUsers")
     * @ORM\JoinColumn(nullable=false)
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
