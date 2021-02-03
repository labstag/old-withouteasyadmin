<?php

namespace Labstag\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Labstag\Repository\AttachmentRepository;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=AttachmentRepository::class)
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Attachment
{

    use SoftDeleteableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid", unique=true)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mimeType;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $size;

    /**
     * @ORM\Column(type="simple_array", nullable=true)
     */
    private $dimensions = [];

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="avatar")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity=Edito::class, mappedBy="fond")
     */
    private $editos;

    /**
     * @ORM\OneToMany(targetEntity=NoteInterne::class, mappedBy="fond")
     */
    private $noteInternes;

    /**
     * @ORM\Column(type="array")
     */
    protected $state;

    public function __construct()
    {
        $this->users        = new ArrayCollection();
        $this->editos       = new ArrayCollection();
        $this->noteInternes = new ArrayCollection();
    }

    public function getState()
    {
        return $this->state;
    }

    public function setState($state)
    {
        $this->state = $state;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(?string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(?int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getDimensions(): ?array
    {
        return $this->dimensions;
    }

    public function setDimensions(?array $dimensions): self
    {
        $this->dimensions = $dimensions;

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
}
