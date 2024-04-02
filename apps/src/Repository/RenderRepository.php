<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Annotation\Trashable;
use Labstag\Entity\Render;
use Labstag\Lib\RepositoryLib;

#[Trashable(url: 'gestion_render_trash')]
class RenderRepository extends RepositoryLib
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Render::class);
    }
}
