<?php

namespace Labstag\Repository;

use Doctrine\Persistence\ManagerRegistry;
use Labstag\Entity\Block;
use Labstag\Entity\Page;
use Labstag\Lib\RepositoryLib;

class BlockRepository extends RepositoryLib
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Block::class);
    }

    public function getBlock(?Page $page)
    {
        $query = $this->createQueryBuilder('c');
        $query->select('c.id');
        $query->innerjoin('c.notinpages', 'p');
        if ($page instanceof Page) {
            $query->andWhere('p.id = :pid');
            $query->setParameter('pid', $page->getId());
        }

        return $query->getQuery();
    }

    /**
     * @return array<string, mixed[]>
     */
    public function getDataByRegion(?Page $page): array
    {
        $regions = [
            'header',
            'content',
            'footer',
        ];
        $data       = [];
        $query      = $this->createQueryBuilder('b');
        $parameters = [];
        if (!is_null($page)) {
            $pageQuery = $this->getBlock($page);
            $query->andWhere($query->expr()->notIn('b.id', $pageQuery->getDQL()));
            $paramsPages = $pageQuery->getParameters()->toArray();
            foreach ($paramsPages as $paramPage) {
                $parameters[$paramPage->getName()] = $paramPage->getValue();
            }
        }

        $query->andWhere(
            'b.region = :region'
        );
        $query->orderBy('b.position', 'ASC');
        foreach ($regions as $region) {
            $parameters['region'] = $region;
            $query->setParameters($parameters);
            $result = $query->getQuery()->getResult();
            dump(['result' => $result]);
            $data[$region] = $result;
        }

        return $data;
    }
}
