<?php

namespace Labstag\Interfaces;

use DateTime;

interface EntityTrashInterface extends EntityInterface
{
    public function getDeletedAt();

    public function setDeletedAt(?DateTime $deletedAt = null);
}
