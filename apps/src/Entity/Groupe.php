<?php

namespace Labstag\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\Collection;
use Labstag\Repository\GroupeRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

/**
 * @ORM\Entity(repositoryClass=GroupeRepository::class)
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Groupe
{
    use SoftDeleteableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid", unique=true)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @Gedmo\Slug(updatable=false, fields={"name"})
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $code;

    /**
     * @ORM\OneToMany(
     *  targetEntity=User::class,
     *  mappedBy="groupe",
     *  cascade={"persist"}
     * )
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setGroupe($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getGroupe() === $this) {
                $user->setGroupe(null);
            }
        }

        return $this;
    }
}
