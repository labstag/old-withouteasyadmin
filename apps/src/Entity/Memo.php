<?php

namespace Labstag\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Labstag\Annotation\Uploadable;
use Labstag\Annotation\UploadableField;
use Labstag\Entity\Traits\StateableEntity;
use Labstag\Repository\MemoRepository;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MemoRepository::class)
 * @Gedmo\SoftDeleteable(fieldName="deletedAt",              timeAware=false)
 * @Uploadable()
 */
class Memo
{
    use SoftDeleteableEntity;

    use StateableEntity;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank
     */
    protected $content;

    /**
     * @ORM\Column(type="datetime",                         nullable=true)
     * @Assert\GreaterThanOrEqual(propertyPath="dateStart")
     */
    protected DateTime $dateEnd;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\LessThanOrEqual(propertyPath="dateEnd")
     */
    protected DateTime $dateStart;

    /**
     * @UploadableField(filename="fond", path="memo/fond", slug="title")
     */
    protected $file;

    /**
     * @ORM\ManyToOne(targetEntity=Attachment::class, inversedBy="noteInternes")
     */
    protected $fond;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid", unique=true)
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="noteInternes")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $refuser;

    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     * @Assert\NotBlank
     */
    protected $title;

    public function __construct()
    {
        $this->dateStart = new DateTime();
        $this->dateEnd   = new DateTime();
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function getDateEnd(): ?DateTime
    {
        return $this->dateEnd;
    }

    public function getDateStart(): ?DateTime
    {
        return $this->dateStart;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function getFond(): ?Attachment
    {
        return $this->fond;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getRefuser(): ?User
    {
        return $this->refuser;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function setDateEnd(?DateTime $dateEnd): self
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    public function setDateStart(DateTime $dateStart): self
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    public function setFile($file): self
    {
        $this->file = $file;

        return $this;
    }

    public function setFond(?Attachment $fond): self
    {
        $this->fond = $fond;

        return $this;
    }

    public function setRefuser(?User $refuser): self
    {
        $this->refuser = $refuser;

        return $this;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }
}
