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

    public $page;

    public function search(
        array $get,
        EntityManagerInterface $entityManager
    ): void
    {
        $entityRepository = $entityManager->getRepository(User::class);
        $categoryRepo = $entityManager->getRepository(Category::class);
        $groupeRepo = $entityManager->getRepository(Groupe::class);
        foreach ($get as $key => $value) {
            if ('published' == $key) {
                if (!empty($value)) {
                    $dateTime = new DateTime();
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
                'user' => $entityRepository->find($value),
                'category' => $categoryRepo->find($value),
                'groupe' => $groupeRepo->find($value),
                default => $value
            };
        }
    }
}
