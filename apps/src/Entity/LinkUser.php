<?php

namespace Labstag\Entity;

use Doctrine\ORM\Mapping as ORM;
use Labstag\Repository\LinkUserRepository;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=LinkUserRepository::class)
 */
class LinkUser extends Link
{

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="linkUsers", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected $refuser;

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
