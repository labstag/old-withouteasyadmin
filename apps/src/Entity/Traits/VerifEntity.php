<?php

namespace Labstag\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait VerifEntity
{

    /**
     * @ORM\Column(type="boolean")
     */
    protected $verif;

    public function isVerif(): ?bool
    {
        return $this->verif;
    }

    public function setVerif(bool $verif): self
    {
        $this->verif = $verif;

        return $this;
    }
}
