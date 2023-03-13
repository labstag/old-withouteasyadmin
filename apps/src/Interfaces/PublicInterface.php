<?php

namespace Labstag\Interfaces;

use Doctrine\Common\Collections\Collection;

interface PublicInterface extends FrontInterface
{
    public function getMetas(): Collection;
}
