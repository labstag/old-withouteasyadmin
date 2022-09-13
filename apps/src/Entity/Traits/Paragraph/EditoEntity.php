<?php

namespace Labstag\Entity\Traits\Paragraph;

use Doctrine\Common\Collections\Collection;
use Labstag\Entity\Edito;
use Labstag\Entity\Paragraph\Edito as ParagraphEdito;
use Labstag\Entity\Paragraph\Edito\Header as EditoHeader;
use Labstag\Entity\Paragraph\Edito\Show as EditoShow;

trait EditoEntity
{

    /**
     * @ORM\ManyToOne(targetEntity=Edito::class, inversedBy="paragraphs")
     */
    private $edito;

    /**
     * @ORM\OneToMany(targetEntity=EditoHeader::class, mappedBy="paragraph", orphanRemoval=true)
     */
    private $editoHeaders;

    /**
     * @ORM\OneToMany(targetEntity=ParagraphEdito::class, mappedBy="paragraph", orphanRemoval=true)
     */
    private $editos;

    /**
     * @ORM\OneToMany(targetEntity=EditoShow::class, mappedBy="paragraph", orphanRemoval=true)
     */
    private $editoShows;

    public function addEdito(ParagraphEdito $edito): self
    {
        if (!$this->editos->contains($edito)) {
            $this->editos[] = $edito;
            $edito->setParagraph($this);
        }

        return $this;
    }

    public function addEditoHeader(EditoHeader $header): self
    {
        if (!$this->editoHeaders->contains($header)) {
            $this->editoHeaders[] = $header;
            $header->setParagraph($this);
        }

        return $this;
    }

    public function addShow(EditoShow $show): self
    {
        if (!$this->editoShows->contains($show)) {
            $this->editoShows[] = $show;
            $show->setParagraph($this);
        }

        return $this;
    }

    public function getEdito(): ?Edito
    {
        return $this->edito;
    }

    /**
     * @return Collection<int, Header>
     */
    public function getEditoHeaders(): Collection
    {
        return $this->editoHeaders;
    }

    /**
     * @return Collection<int, Edito>
     */
    public function getEditos(): Collection
    {
        return $this->editos;
    }

    /**
     * @return Collection<int, Show>
     */
    public function getEditoShows(): Collection
    {
        return $this->editoShows;
    }

    public function removeEdito(ParagraphEdito $edito): self
    {
        if ($this->editos->removeElement($edito)) {
            // set the owning side to null (unless already changed)
            if ($edito->getParagraph() === $this) {
                $edito->setParagraph(null);
            }
        }

        return $this;
    }

    public function removeEditoHeader(EditoHeader $header): self
    {
        if ($this->editoHeaders->removeElement($header)) {
            // set the owning side to null (unless already changed)
            if ($header->getParagraph() === $this) {
                $header->setParagraph(null);
            }
        }

        return $this;
    }

    public function removeShow(EditoShow $show): self
    {
        if ($this->editoShows->removeElement($show)) {
            // set the owning side to null (unless already changed)
            if ($show->getParagraph() === $this) {
                $show->setParagraph(null);
            }
        }

        return $this;
    }

    public function setEdito(?Edito $edito): self
    {
        $this->edito = $edito;

        return $this;
    }
}
