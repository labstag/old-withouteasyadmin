<?php

namespace Labstag\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Labstag\Repository\MenuRepository;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MenuRepository::class)
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Menu
{
    use SoftDeleteableEntity;

    /**
     * @ORM\OneToMany(
     *  targetEntity=Menu::class,
     *  mappedBy="parent",
     *  cascade={"persist"},
     *  orphanRemoval=true
     * )
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $children;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $clef;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    protected array $data = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $icon;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid", unique=true)
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity=Menu::class, inversedBy="children")
     * @ORM\JoinColumn(
     *  name="parent_id",
     *  referencedColumnName="id",
     *  onDelete="SET NULL"
     * )
     *
     * @var null|Menu
     */
    protected $parent;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotNull
     */
    protected int $position;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $separateur;

    public function __construct()
    {
        $this->children   = new ArrayCollection();
        $this->separateur = false;
    }

    public function __toString()
    {
        return implode(
            ' ',
            [
                $this->getId(),
                '-',
                '('.$this->getClef().')',
                '-',
                $this->getName(),
            ]
        );
    }

    public function addChild(Menu $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setParent($this);
        }

        return $this;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function getClef(): ?string
    {
        return $this->clef;
    }

    public function getData(): ?array
    {
        return is_array($this->data) ? $this->data : [];
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getParent(): ?Menu
    {
        return $this->parent;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function getSeparateur(): ?bool
    {
        return $this->separateur;
    }

    public function isSeparateur(): ?bool
    {
        return $this->separateur;
    }

    public function removeChild(Menu $child): self
    {
        if ($this->children->removeElement($child)) {
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }

    public function setClef(?string $clef): self
    {
        $this->clef = $clef;

        return $this;
    }

    public function setData(?array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setParent(Menu $parent): void
    {
        $this->parent = $parent;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function setSeparateur(bool $separateur): self
    {
        $this->separateur = $separateur;

        return $this;
    }
}
