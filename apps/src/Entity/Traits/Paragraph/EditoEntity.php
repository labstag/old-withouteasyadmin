<?php

namespace Labstag\Entity\Traits\Paragraph;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Labstag\Entity\Edito;
use Labstag\Entity\Paragraph\Edito as ParagraphEdito;
use Labstag\Entity\Paragraph\Edito\Header as EditoHeader;
use Labstag\Entity\Paragraph\Edito\Show as EditoShow;

trait EditoEntity
{
    #[ORM\ManyToOne(
        targetEntity: Edito::class,
        inversedBy: 'paragraphs',
        cascade: ['persist']
    )
    ]
    private ?Edito $edito = null;

    #[ORM\OneToMany(
        targetEntity: EditoHeader::class,
        mappedBy: 'paragraph',
        cascade: ['persist'],
        orphanRemoval: true
    )
    ]
    private Collection $editoHeaders;

    #[ORM\OneToMany(
        targetEntity: ParagraphEdito::class,
        mappedBy: 'paragraph',
        cascade: ['persist'],
        orphanRemoval: true
    )
    ]
    private Collection $editos;

    #[ORM\OneToMany(
        targetEntity: EditoShow::class,
        mappedBy: 'paragraph',
        cascade: ['persist'],
        orphanRemoval: true
    )
    ]
    private Collection $editoShows;

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

    public function getEditoShows(): Collection
    {
        return $this->editoShows;
    }

    public function removeEdito(ParagraphEdito $paragraphEdito): self
    {
        $this->removeElementEdito($this->editos, $paragraphEdito);

        return $this;
    }

    public function removeEditoHeader(EditoHeader $editoHeader): self
    {
        $this->removeElementEdito($this->editoHeaders, $editoHeader);

        return $this;
    }

    public function removeShow(EditoShow $editoShow): self
    {
        $this->removeElementEdito($this->editoShows, $editoShow);

        return $this;
    }

    public function setEdito(?Edito $edito): self
    {
        $this->edito = $edito;

        return $this;
    }

    private function removeElementEdito(
        Collection $element,
        mixed $variable
    ): void {
        if ($element->removeElement($variable) && $variable->getParagraph() === $this) {
            $variable->setParagraph(null);
        }
    }
}
