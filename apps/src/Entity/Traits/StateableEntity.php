<?php

namespace Labstag\Entity\Traits;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

trait StateableEntity
{

    #[ORM\Column(type: 'array')]
    private $state;

    #[Gedmo\Timestampable(on: 'change', field: ['state'])]
    #[ORM\Column(name: 'state_changed', type: 'datetime', nullable: true)]
    private DateTime $stateChanged;

    public function getState()
    {
        return $this->state;
    }

    public function getStateChanged(): DateTime
    {
        return $this->stateChanged;
    }

    public function setState($state)
    {
        $this->state = $state;
    }
}
