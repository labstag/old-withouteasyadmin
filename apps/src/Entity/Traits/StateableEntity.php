<?php

namespace Labstag\Entity\Traits;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait StateableEntity
{
    #[ORM\Column(type: 'array')]
    private mixed $state = null;

    #[Gedmo\Timestampable(on: 'change', field: ['state'])]
    #[ORM\Column(name: 'state_changed', type: 'datetime', nullable: true)]
    private DateTime $stateChanged;

    public function getState(): mixed
    {
        return $this->state;
    }

    public function getStateChanged(): DateTime
    {
        return $this->stateChanged;
    }

    public function setState(mixed $state): self
    {
        $this->state = $state;

        return $this;
    }
}
