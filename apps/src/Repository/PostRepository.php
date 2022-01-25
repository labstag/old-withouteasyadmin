<?php

namespace Labstag\Repository;

use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Labstag\Annotation\Trashable;
use Labstag\Entity\Post;
use Labstag\Lib\ServiceEntityRepositoryLib;

/**
 * @Trashable(url="admin_post_trash")
 */
class PostRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function findDateArchive()
    {
        $queryBuilder = $this->createQueryBuilder('p');
        $query        = $queryBuilder->select(
            'date_format(p.published,\'%Y-%m\') as code, p.published, COUNT(p)'
        );
        $query->where(
            'p.state LIKE :state'
        );
        $query->orderBy('p.published', 'DESC');
        $query->groupBy('code');
        $query->orderBy('code', 'DESC');
        $query->setParameters(
            ['state' => '%publie%']
        );

        return $query->getQuery()->getResult();
    }

    public function findPublier()
    {
        $queryBuilder = $this->createQueryBuilder('p');
        $query        = $queryBuilder->innerjoin('p.refuser', 'u');
        $query->where(
            'p.state LIKE :state'
        );
        $query->orderBy('p.published', 'DESC');
        $query->setParameters(
            ['state' => '%publie%']
        );

        return $query->getQuery();
    }

    public function findPublierArchive($published)
    {
        $queryBuilder = $this->createQueryBuilder('p');
        $query        = $queryBuilder->innerjoin('p.refuser', 'u');
        $query->where('p.state LIKE :state');
        $query->andWhere('date_format(p.published,\'%Y-%m\') = :published');
        $query->orderBy('p.published', 'DESC');
        $query->setParameters(
            [
                'state'     => '%publie%',
                'published' => $published,
            ]
        );

        return $query->getQuery();
    }

    public function findPublierCategory($code)
    {
        $queryBuilder = $this->createQueryBuilder('p');
        $query        = $queryBuilder->where('p.state LIKE :state');
        $query->orderBy('p.published', 'DESC');
        $query->leftJoin('p.refcategory', 'c');
        $query->andWhere('c.slug=:slug');
        $query->setParameters(
            [
                'slug'  => $code,
                'state' => '%publie%',
            ]
        );

        return $query->getQuery();
    }

    public function findPublierLibelle($code)
    {
        $queryBuilder = $this->createQueryBuilder('p');
        $query        = $queryBuilder->where('p.state LIKE :state');
        $query->orderBy('p.published', 'DESC');
        $query->leftJoin('p.libelles', 'l');
        $query->andWhere('l.slug=:slug');
        $query->setParameters(
            [
                'slug'  => $code,
                'state' => '%publie%',
            ]
        );

        return $query->getQuery();
    }

    public function findPublierUsername($username)
    {
        $queryBuilder = $this->createQueryBuilder('p');
        $query        = $queryBuilder->leftJoin('p.refuser', 'u');
        $query        = $query->where('p.state LIKE :state');
        $query->andWhere('u.username = :username');
        $query->orderBy('p.published', 'DESC');
        $query->setParameters(
            [
                'state'    => '%publie%',
                'username' => $username,
            ]
        );

        return $query->getQuery();
    }

    protected function setQuery(QueryBuilder $query, array $get): QueryBuilder
    {
        $this->setQueryEtape($query, $get);
        $this->setQueryPublished($query, $get);
        $this->setQueryTitle($query, $get);
        $this->setQueryRefUser($query, $get);
        $this->setQueryRefCategory($query, $get);

        return $query;
    }

    protected function setQueryPublished(QueryBuilder &$query, array $get)
    {
        if (!isset($get['published']) || empty($get['published'])) {
            return;
        }

        $query->andWhere('DATE(a.published) = :published');
        $query->setParameter('published', $get['published']);
    }

    protected function setQueryTitle(QueryBuilder &$query, array $get)
    {
        if (!isset($get['title']) || empty($get['title'])) {
            return;
        }

        $query->andWhere('a.title LIKE :title');
        $query->setParameter('title', '%'.$get['title'].'%');
    }
}
