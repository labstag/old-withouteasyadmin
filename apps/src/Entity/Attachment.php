<?php

namespace Labstag\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Labstag\Entity\Traits\StateableEntity;
use Labstag\Repository\AttachmentRepository;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

/**
 * @ORM\Entity(repositoryClass=AttachmentRepository::class)
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Attachment
{
    use SoftDeleteableEntity;

    use StateableEntity;

    /**
     * @ORM\OneToMany(targetEntity=Edito::class, mappedBy="fond", orphanRemoval=true)
     */
    protected $editos;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\Column(type="guid", unique=true)
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $mimeType;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity=Memo::class, mappedBy="fond", orphanRemoval=true)
     */
    protected $noteInternes;

    /**
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="img", orphanRemoval=true)
     */
    protected $posts;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $size;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="avatar", orphanRemoval=true)
     */
    protected $users;

    /**
     * @ORM\OneToMany(targetEntity=Bookmark::class, mappedBy="img", orphanRemoval=true)
     */
    private $bookmarks;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $code;

    public function __construct()
    {
        $this->users        = new ArrayCollection();
        $this->posts        = new ArrayCollection();
        $this->editos       = new ArrayCollection();
        $this->noteInternes = new ArrayCollection();
        $this->bookmarks    = new ArrayCollection();
    }

    public function addBookmark(Bookmark $bookmark): self
    {
        if (!$this->bookmarks->contains($bookmark)) {
            $this->bookmarks[] = $bookmark;
            $bookmark->setImg($this);
        }

        return $this;
    }

    public function addEdito(Edito $edito): self
    {
        if (!$this->editos->contains($edito)) {
            $this->editos[] = $edito;
            $edito->setFond($this);
        }

        return $this;
    }

    public function addMemo(Memo $noteInterne): self
    {
        if (!$this->noteInternes->contains($noteInterne)) {
            $this->noteInternes[] = $noteInterne;
            $noteInterne->setFond($this);
        }

        return $this;
    }

    public function addNoteInterne(Memo $noteInterne): self
    {
        if (!$this->noteInternes->contains($noteInterne)) {
            $this->noteInternes[] = $noteInterne;
            $noteInterne->setFond($this);
        }

        return $this;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setImg($this);
        }

        return $this;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setAvatar($this);
        }

        return $this;
    }

    public function getBookmarks(): Collection
    {
        return $this->bookmarks;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function getEditos(): Collection
    {
        return $this->editos;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getMemos(): Collection
    {
        return $this->noteInternes;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getNoteInternes(): Collection
    {
        return $this->noteInternes;
    }

    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function removeBookmark(Bookmark $bookmark): self
    {
        if ($this->bookmarks->removeElement($bookmark)) {
            // set the owning side to null (unless already changed)
            if ($bookmark->getImg() === $this) {
                $bookmark->setImg(null);
            }
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

    public function removeMemo(Memo $noteInterne): self
    {
        if ($this->noteInternes->removeElement($noteInterne)) {
            // set the owning side to null (unless already changed)
            if ($noteInterne->getFond() === $this) {
                $noteInterne->setFond(null);
            }
        }

        return $this;
    }

    public function removeNoteInterne(Memo $noteInterne): self
    {
        if ($this->noteInternes->removeElement($noteInterne)) {
            // set the owning side to null (unless already changed)
            if ($noteInterne->getFond() === $this) {
                $noteInterne->setFond(null);
            }
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getImg() === $this) {
                $post->setImg(null);
            }
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

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function setMimeType(?string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setSize(?int $size): self
    {
        $this->size = $size;

        return $this;
    }
}
