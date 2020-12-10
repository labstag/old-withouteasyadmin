<?php

namespace Labstag\Entity;

use Labstag\Repository\EmailUserRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EmailUserRepository::class)
 */
class EmailUser extends Email
{

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="emailUsers")
     * @Assert\NotBlank
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
