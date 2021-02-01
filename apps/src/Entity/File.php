<?php

namespace Labstag\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Labstag\Repository\FileRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=FileRepository::class)
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class File
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
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $extension;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="avatar")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity=NoteInterne::class, mappedBy="fond")
     */
    private $noteInternes;

    /**
     * @ORM\OneToMany(targetEntity=Edito::class, mappedBy="fond")
     */
    private $editos;

    public function __construct()
    {
        $this->users        = new ArrayCollection();
        $this->noteInternes = new ArrayCollection();
        $this->editos       = new ArrayCollection();
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

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(string $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(string $extension): self
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setAvatar($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getAvatar() === $this) {
                $user->setAvatar(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|NoteInterne[]
     */
    public function getNoteInternes(): Collection
    {
        return $this->noteInternes;
    }

    public function addNoteInterne(NoteInterne $noteInterne): self
    {
        if (!$this->noteInternes->contains($noteInterne)) {
            $this->noteInternes[] = $noteInterne;
            $noteInterne->setFond($this);
        }

        return $this;
    }

    public function removeNoteInterne(NoteInterne $noteInterne): self
    {
        if ($this->noteInternes->removeElement($noteInterne)) {
            // set the owning side to null (unless already changed)
            if ($noteInterne->getFond() === $this) {
                $noteInterne->setFond(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Edito[]
     */
    public function getEditos(): Collection
    {
        return $this->editos;
    }

    public function addEdito(Edito $edito): self
    {
        if (!$this->editos->contains($edito)) {
            $this->editos[] = $edito;
            $edito->setFond($this);
        }

        return $this;
    }

    public function removeEdito(Edito $edito): self
    {
        if ($this->editos->removeElement($edito)) {
            // set the owning side to null (unless already changed)
            if ($edito->getFond() === $this) {
                $edito->setFond(null);
            }
        }

        return $this;
    }
}
