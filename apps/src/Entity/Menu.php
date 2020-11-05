<?php

namespace Labstag\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Labstag\Repository\MenuRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Gedmo\Tree(type="nested")
 * @ORM\Entity(repositoryClass=MenuRepository::class)
 */
class Menu
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid", unique=true)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $icon;

    /**
     * @ORM\Column(type="integer")
     */
    private $position;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private $data = [];

    /**
     * @ORM\Column(type="boolean")
     */
    private $separateur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $clef;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Menu", inversedBy="children")
     * @ORM\JoinColumn(
     *  name="parent_id",
     *  referencedColumnName="id",
     *  onDelete="SET NULL"
     * )
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Menu", mappedBy="parent")
     * @ORM\OrderBy({"position" = "ASC"})
     */
    private $children;

    public function __toString()
    {
        return implode(
            ' ',
            [
                $this->getId(),
                '-',
                '('.$this->getClef().')',
                '-',
                $this->getLibelle(),
            ]
        );
    }

    public function __construct()
    {
        $this->separateur = false;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getData(): ?array
    {
        return $this->data;
    }

    public function setData(?array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getSeparateur(): ?bool
    {
        return $this->separateur;
    }

    public function setSeparateur(bool $separateur): self
    {
        $this->separateur = $separateur;

        return $this;
    }

    public function getClef(): ?string
    {
        return $this->clef;
    }

    public function setClef(string $clef): self
    {
        $this->clef = $clef;

        return $this;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function setParent(Menu $parent)
    {
        $this->parent = $parent;
    }

    public function getParent(): ?Menu
    {
        return $this->parent;
    }
}
