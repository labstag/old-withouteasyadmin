<?php

namespace Labstag\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Labstag\Entity\Block\Breadcrumb;
use Labstag\Entity\Block\Custom;
use Labstag\Entity\Block\Flashbag;
use Labstag\Entity\Block\Footer;
use Labstag\Entity\Block\Header;
use Labstag\Entity\Block\Html;
use Labstag\Entity\Block\Navbar;
use Labstag\Entity\Block\Paragraph;
use Labstag\Repository\BlockRepository;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

/**
 * @ORM\Entity(repositoryClass=BlockRepository::class)
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Block
{
    use SoftDeleteableEntity;

    /**
     * @ORM\OneToMany(targetEntity=Breadcrumb::class, mappedBy="block")
     */
    private $breadcrumbs;

    /**
     * @ORM\OneToMany(targetEntity=Custom::class, mappedBy="block")
     */
    private $customs;

    /**
     * @ORM\OneToMany(targetEntity=Flashbag::class, mappedBy="block")
     */
    private $flashbags;

    /**
     * @ORM\OneToMany(targetEntity=Footer::class, mappedBy="block", cascade={"persist"}, orphanRemoval=true)
     */
    private $footers;

    /**
     * @ORM\OneToMany(targetEntity=Header::class, mappedBy="block", cascade={"persist"}, orphanRemoval=true)
     */
    private $headers;

    /**
     * @ORM\OneToMany(targetEntity=Html::class, mappedBy="block", cascade={"persist"}, orphanRemoval=true)
     */
    private $htmls;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\Column(type="guid", unique=true)
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity=Navbar::class, mappedBy="block")
     */
    private $menu;

    /**
     * @ORM\OneToMany(targetEntity=Paragraph::class, mappedBy="block", cascade={"persist"}, orphanRemoval=true)
     */
    private $paragraphs;

    /**
     * @ORM\Column(type="integer")
     */
    private int $position = 0;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $region;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    public function __construct()
    {
        $this->headers     = new ArrayCollection();
        $this->htmls       = new ArrayCollection();
        $this->footers     = new ArrayCollection();
        $this->paragraphs  = new ArrayCollection();
        $this->breadcrumbs = new ArrayCollection();
        $this->menu        = new ArrayCollection();
        $this->flashbags   = new ArrayCollection();
        $this->customs     = new ArrayCollection();
    }

    public function addBreadcrumb(Breadcrumb $breadcrumb): self
    {
        if (!$this->breadcrumbs->contains($breadcrumb)) {
            $this->breadcrumbs[] = $breadcrumb;
            $breadcrumb->setBlock($this);
        }

        return $this;
    }

    public function addCustom(Custom $custom): self
    {
        if (!$this->customs->contains($custom)) {
            $this->customs[] = $custom;
            $custom->setBlock($this);
        }

        return $this;
    }

    public function addFlashbag(Flashbag $flashbag): self
    {
        if (!$this->flashbags->contains($flashbag)) {
            $this->flashbags[] = $flashbag;
            $flashbag->setBlock($this);
        }

        return $this;
    }

    public function addFooter(Footer $footer): self
    {
        if (!$this->footers->contains($footer)) {
            $this->footers[] = $footer;
            $footer->setBlock($this);
        }

        return $this;
    }

    public function addHeader(Header $header): self
    {
        if (!$this->headers->contains($header)) {
            $this->headers[] = $header;
            $header->setBlock($this);
        }

        return $this;
    }

    public function addHtml(Html $html): self
    {
        if (!$this->htmls->contains($html)) {
            $this->htmls[] = $html;
            $html->setBlock($this);
        }

        return $this;
    }

    public function addMenu(Navbar $menu): self
    {
        if (!$this->menu->contains($menu)) {
            $this->menu[] = $menu;
            $menu->setBlock($this);
        }

        return $this;
    }

    public function addParagraph(Paragraph $paragraph): self
    {
        if (!$this->paragraphs->contains($paragraph)) {
            $this->paragraphs[] = $paragraph;
            $paragraph->setBlock($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Breadcrumb>
     */
    public function getBreadcrumbs(): Collection
    {
        return $this->breadcrumbs;
    }

    /**
     * @return Collection<int, Custom>
     */
    public function getCustoms(): Collection
    {
        return $this->customs;
    }

    /**
     * @return Collection<int, Flashbag>
     */
    public function getFlashbags(): Collection
    {
        return $this->flashbags;
    }

    /**
     * @return Collection<int, Footer>
     */
    public function getFooters(): Collection
    {
        return $this->footers;
    }

    /**
     * @return Collection<int, Header>
     */
    public function getHeaders(): Collection
    {
        return $this->headers;
    }

    /**
     * @return Collection<int, Html>
     */
    public function getHtmls(): Collection
    {
        return $this->htmls;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Navbar>
     */
    public function getMenu(): Collection
    {
        return $this->menu;
    }

    /**
     * @return Collection<int, Paragraph>
     */
    public function getParagraphs(): Collection
    {
        return $this->paragraphs;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function removeBreadcrumb(Breadcrumb $breadcrumb): self
    {
        if ($this->breadcrumbs->removeElement($breadcrumb)) {
            // set the owning side to null (unless already changed)
            if ($breadcrumb->getBlock() === $this) {
                $breadcrumb->setBlock(null);
            }
        }

        return $this;
    }

    public function removeCustom(Custom $custom): self
    {
        if ($this->customs->removeElement($custom)) {
            // set the owning side to null (unless already changed)
            if ($custom->getBlock() === $this) {
                $custom->setBlock(null);
            }
        }

        return $this;
    }

    public function removeFlashbag(Flashbag $flashbag): self
    {
        if ($this->flashbags->removeElement($flashbag)) {
            // set the owning side to null (unless already changed)
            if ($flashbag->getBlock() === $this) {
                $flashbag->setBlock(null);
            }
        }

        return $this;
    }

    public function removeFooter(Footer $footer): self
    {
        if ($this->footers->removeElement($footer)) {
            // set the owning side to null (unless already changed)
            if ($footer->getBlock() === $this) {
                $footer->setBlock(null);
            }
        }

        return $this;
    }

    public function removeHeader(Header $header): self
    {
        if ($this->headers->removeElement($header)) {
            // set the owning side to null (unless already changed)
            if ($header->getBlock() === $this) {
                $header->setBlock(null);
            }
        }

        return $this;
    }

    public function removeHtml(Html $html): self
    {
        if ($this->htmls->removeElement($html)) {
            // set the owning side to null (unless already changed)
            if ($html->getBlock() === $this) {
                $html->setBlock(null);
            }
        }

        return $this;
    }

    public function removeMenu(Navbar $menu): self
    {
        if ($this->menu->removeElement($menu)) {
            // set the owning side to null (unless already changed)
            if ($menu->getBlock() === $this) {
                $menu->setBlock(null);
            }
        }

        return $this;
    }

    public function removeParagraph(Paragraph $paragraph): self
    {
        if ($this->paragraphs->removeElement($paragraph)) {
            // set the owning side to null (unless already changed)
            if ($paragraph->getBlock() === $this) {
                $paragraph->setBlock(null);
            }
        }

        return $this;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function setRegion(string $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
