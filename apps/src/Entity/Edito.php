<?php

namespace Labstag\Entity;

use DateTimeImmutable;
use Symfony\Component\HttpFoundation\File\File;
use Labstag\Repository\EditoRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Mapping\Annotation as Gedmo;
use Labstag\Annotation\Uploadable;
use Labstag\Annotation\UploadableField;

/**
 * @ORM\Entity(repositoryClass=EditoRepository::class)
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @Uploadable()
 */
class Edito
{

    use SoftDeleteableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid", unique=true)
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    protected $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     */
    protected $content;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="editos")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $refuser;

    /**
     * @ORM\Column(type="array")
     */
    protected $state;

    /**
     * @ORM\ManyToOne(targetEntity=Attachment::class, inversedBy="editos")
     */
    private $fond;

    /**
     * @UploadableField(filename="fond", path="edito/fond")
     */
    protected $file;

    public function __toString()
    {
        return $this->getTitle();
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getRefuser(): ?User
    {
        return $this->refuser;
    }

    public function setRefuser(?User $refuser): self
    {
        $this->refuser = $refuser;

        return $this;
    }

    public function getFond(): ?Attachment
    {
        return $this->fond;
    }

    public function setFond(?Attachment $fond): self
    {
        $this->fond = $fond;

        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file): self
    {
        $this->file = $file;

        return $this;
    }
}
