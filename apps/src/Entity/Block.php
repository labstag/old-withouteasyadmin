<?php

namespace Labstag\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Labstag\Entity\Block\Footer;
use Labstag\Entity\Block\Header;
use Labstag\Entity\Block\Html;
use Labstag\Repository\BlockRepository;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

/**
 * @ORM\Entity(repositoryClass=BlockRepository::class)
 */
class Block
{

    /**
     * @ORM\OneToMany(targetEntity=Footer::class, mappedBy="block")
     */
    private $footers;

    /**
     * @ORM\OneToMany(targetEntity=Header::class, mappedBy="block")
     */
    private $headers;

    /**
     * @ORM\OneToMany(targetEntity=Html::class, mappedBy="block")
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
     * @ORM\Column(type="string", length=255, unique=true, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    public function __construct()
    {
        $this->headers = new ArrayCollection();
        $this->htmls   = new ArrayCollection();
        $this->footers = new ArrayCollection();
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getType(): ?string
    {
        return $this->type;
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
