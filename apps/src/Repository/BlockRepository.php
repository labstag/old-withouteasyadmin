<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Block;
use Labstag\Lib\ServiceEntityRepositoryLib;

class BlockRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Block::class);
    }

    /**
     * @return array<string, mixed[]>
     */
    public function getDataByRegion(): array
    {
        $types = [
            'header',
            'content',
            'footer',
        ];
        $data = [];
        foreach ($types as $type) {
            $data[$type] = $this->findBy(
                ['region' => $type],
                ['position' => 'ASC']
            );
        }

        return $data;
    }
}
