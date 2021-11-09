<?php

namespace Labstag\Entity;

use Doctrine\ORM\Mapping as ORM;
use Labstag\Repository\LinkUserRepository;

/**
 * @ORM\Entity(repositoryClass=LinkUserRepository::class)
 */
class LinkUser extends Link
{

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="LinkUsers")
     * @ORM\JoinColumn(nullable=false)
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