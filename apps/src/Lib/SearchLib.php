<?php

namespace Labstag\Lib;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Labstag\Entity\Category;
use Labstag\Entity\Groupe;
use Labstag\Entity\User;

abstract class SearchLib
{

    public $limit;

    public function search(
        array $get,
        EntityManagerInterface $entityManager
    ): void
    {
        $entityRepository = $entityManager->getRepository(User::class);
        $categoryRepo = $entityManager->getRepository(Category::class);
        $groupeRepo = $entityManager->getRepository(Groupe::class);
        $dateTime = new DateTime();
        foreach ($get as $key => $value) {
            $this->{$key} = $value;
            if ('published' == $key) {
                if (!empty($value)) {
                    [
                        $year,
                        $month,
                        $day,
                    ] = explode('-', (string) $value);
                    $dateTime->setDate((int) $year, (int) $month, (int) $day);
                    $this->{$key} = $dateTime;

                    continue;
                }

                $this->{$key} = null;

                continue;
            }

            $this->{$key} = match ($key) {
                'refuser' => $entityRepository->find($value),
                'refcategory' => $categoryRepo->find($value),
                'refgroup' => $groupeRepo->find($value),
                default => $this->{$key}
            };
        }
    }
}
