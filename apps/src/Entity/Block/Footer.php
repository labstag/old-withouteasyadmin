<?php

namespace Labstag\Entity\Block;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Labstag\Entity\Block;
use Labstag\Repository\Block\FooterRepository;
use Stringable;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

/**
 * @ORM\Table(name="block_footer")
 *
 * @ORM\Entity(repositoryClass=FooterRepository::class)
 */
class Footer implements Stringable
{

    /**
     * @ORM\ManyToOne(targetEntity=Block::class, inversedBy="footers", cascade={"persist"})
     */
    private ?Block $block = null;

    /**
     * @ORM\Id
     *
     * @ORM\GeneratedValue(strategy="CUSTOM")
     *
     * @ORM\Column(type="guid", unique=true)
     *
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity=Link::class, mappedBy="footer", cascade={"persist"}, orphanRemoval=true)
     */
    private $links;

    public function __construct()
    {
        $this->links = new ArrayCollection();
    }

    public function __toString(): string
    {
        return (string) $this->getBlock()->getTitle();
    }

    public function addLink(Link $link): self
    {
        if (!$this->links->contains($link)) {
            $this->links[] = $link;
            $link->setFooter($this);
        }

        return $this;
    }

    public function getBlock(): ?Block
    {
        return $this->block;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Link>
     */
    public function getLinks(): Collection
    {
        return $this->links;
    }

    public function removeLink(Link $link): self
    {
        // set the owning side to null (unless already changed)
        if ($this->links->removeElement($link) && $link->getFooter() === $this) {
            $link->setFooter(null);
        }

        return $this;
    }

    public function setBlock(?Block $block): self
    {
        $this->block = $block;

        return $this;
    }
}
