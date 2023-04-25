<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Annotation\Trashable;
use Labstag\Entity\Template;
use Labstag\Lib\RepositoryLib;

#[Trashable(url: 'admin_template_trash')]
class TemplateRepository extends RepositoryLib
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Template::class);
    }
}
