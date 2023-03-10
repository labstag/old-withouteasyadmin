<?php

namespace Labstag\Repository;

use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Labstag\Annotation\Trashable;
use Labstag\Entity\Post;
use Labstag\Lib\ServiceEntityRepositoryLib;

#[Trashable(url: 'admin_post_trash')]
class PostRepository extends ServiceEntityRepositoryLib
{
    public function __construct(ManagerRegistry $managerRegistry)
    {
        parent::__construct($managerRegistry, Post::class);
    }

    public function findDateArchive(): mixed
    {
        $query = $this->createQueryBuilder('p');
        $query->select(
            "date_format(p.published,'%Y-%m') as code, p.published, COUNT(p)"
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

    public function findPublier(): Query
    {
        $query = $this->createQueryBuilder('p');
        $query->innerjoin('p.user', 'u');
        $query->where(
            'p.state LIKE :state'
        );
        $query->orderBy('p.published', 'DESC');
        $query->setParameters(
            ['state' => '%publie%']
        );

        return $query->getQuery();
    }

    public function findPublierArchive(int $published): Query
    {
        $query = $this->createQueryBuilder('p');
        $query->innerjoin('p.user', 'u');
        $query->where('p.state LIKE :state');
        $query->andWhere("date_format(p.published,'%Y-%m') = :published");
        $query->orderBy('p.published', 'DESC');
        $query->setParameters(
            [
                'state'     => '%publie%',
                'published' => $published,
            ]
        );

        return $query->getQuery();
    }

    public function findPublierCategory(string $code): Query
    {
        $query = $this->createQueryBuilder('p');
        $query->where('p.state LIKE :state');
        $query->orderBy('p.published', 'DESC');
        $query->leftJoin('p.category', 'c');
        $query->andWhere('c.slug=:slug');
        $query->setParameters(
            [
                'slug'  => $code,
                'state' => '%publie%',
            ]
        );

        return $query->getQuery();
    }

    public function findPublierLibelle(string $code): Query
    {
        $query = $this->createQueryBuilder('p');
        $query->where('p.state LIKE :state');
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

    public function findPublierUsername(string $username): Query
    {
        $query = $this->createQueryBuilder('p');
        $query->leftJoin('p.user', 'u');
        $query->where('p.state LIKE :state');
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
}
