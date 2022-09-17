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

    public function addEdito(ParagraphEdito $paragraphEdito): self
    {
        if (!$this->editos->contains($paragraphEdito)) {
            $this->editos[] = $paragraphEdito;
            $paragraphEdito->setParagraph($this);
        }

        return $this;
    }

    public function addEditoHeader(EditoHeader $editoHeader): self
    {
        if (!$this->editoHeaders->contains($editoHeader)) {
            $this->editoHeaders[] = $editoHeader;
            $editoHeader->setParagraph($this);
        }

        return $this;
    }

    public function addShow(EditoShow $editoShow): self
    {
        if (!$this->editoShows->contains($editoShow)) {
            $this->editoShows[] = $editoShow;
            $editoShow->setParagraph($this);
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

    public function removeEdito(ParagraphEdito $paragraphEdito): self
    {
        // set the owning side to null (unless already changed)
        if ($this->editos->removeElement($paragraphEdito) && $paragraphEdito->getParagraph() === $this) {
            $paragraphEdito->setParagraph(null);
        }

        return $this;
    }

    public function removeEditoHeader(EditoHeader $editoHeader): self
    {
        // set the owning side to null (unless already changed)
        if ($this->editoHeaders->removeElement($editoHeader) && $editoHeader->getParagraph() === $this) {
            $editoHeader->setParagraph(null);
        }

        return $this;
    }

    public function removeShow(EditoShow $editoShow): self
    {
        // set the owning side to null (unless already changed)
        if ($this->editoShows->removeElement($editoShow) && $editoShow->getParagraph() === $this) {
            $editoShow->setParagraph(null);
        }

        return $this;
    }

    public function setEdito(?Edito $edito): self
    {
        $this->edito = $edito;

        return $this;
    }
}
