<?php

namespace Labstag\Interfaces;

use DateTime;

interface EntityTrashInterface extends EntityInterface
{
    /**
     * @phpstan-ignore-next-line
     */
    public function getDeletedAt();

    /**
     * @phpstan-ignore-next-line
     */
    public function setDeletedAt(?DateTime $deletedAt = null);
}
