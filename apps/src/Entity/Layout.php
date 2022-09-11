<?php

namespace Labstag\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Labstag\Entity\Block\Custom;
use Labstag\Repository\LayoutRepository;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

/**
 * @ORM\Entity(repositoryClass=LayoutRepository::class)
 */
class Layout
{
    use SoftDeleteableEntity;

    /**
     * @ORM\ManyToOne(targetEntity=Custom::class, inversedBy="layouts")
     */
    private $custom;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\Column(type="guid", unique=true)
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=Paragraph::class, mappedBy="layout", cascade={"persist"}, orphanRemoval=true)
     */
    private $paragraphs;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $url = [];

    public function __construct()
    {
        $this->paragraphs = new ArrayCollection();
    }

    public function addParagraph(Paragraph $paragraph): self
    {
        if (!$this->paragraphs->contains($paragraph)) {
            $this->paragraphs[] = $paragraph;
            $paragraph->setLayout($this);
        }

        return $this;
    }

    public function getCustom(): ?Custom
    {
        return $this->custom;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return Collection<int, Paragraph>
     */
    public function getParagraphs(): Collection
    {
        return $this->paragraphs;
    }

    public function getUrl(): ?array
    {
        return $this->url;
    }

    public function removeParagraph(Paragraph $paragraph): self
    {
        if ($this->paragraphs->removeElement($paragraph)) {
            // set the owning side to null (unless already changed)
            if ($paragraph->getLayout() === $this) {
                $paragraph->setLayout(null);
            }
        }

        return $this;
    }

    public function setCustom(?Custom $custom): self
    {
        $this->custom = $custom;

        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setUrl(?array $url): self
    {
        $this->url = $url;

        return $this;
    }
}
