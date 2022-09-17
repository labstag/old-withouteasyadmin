<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Render;
use Labstag\Lib\ServiceEntityRepositoryLib;

/**
 * @Trashable(url="admin_render_trash")
 */
class RenderRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Render::class);
    }
}
