<?php

namespace Labstag\Entity;

use Doctrine\ORM\Mapping as ORM;
use Labstag\Repository\PhoneUserRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PhoneUserRepository::class)
 */
class PhoneUser extends Phone
{

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="phoneUsers", cascade={"persist"})
     *
     * @Assert\NotBlank
     */
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
