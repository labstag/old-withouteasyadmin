<?php

namespace Labstag\Lib;

use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Labstag\Entity\Category;
use Labstag\Entity\Groupe;
use Labstag\Entity\User;

abstract class SearchLib
{

    public int $limit = 10;

    public int $page = 0;

    public function search(
        array $get,
        EntityManagerInterface $entityManager
    ): void
    {
        $entityRepository = $entityManager->getRepository(User::class);
        $categoryRepo = $entityManager->getRepository(Category::class);
        $groupeRepo = $entityManager->getRepository(Groupe::class);
        foreach ($get as $key => $value) {
            if (!isset($this->{$key})) {
                continue;
            }

            if ('published' == $key) {
                if (!empty($value)) {
                    $dateTime = new DateTime();
                    [
                        $year,
                        $month,
                        $day,
                    ] = explode('-', (string) $value);
                    $dateTime->setDate((int) $year, (int) $month, (int) $day);
                    $value = $dateTime;

                    continue;
                }

                $value = null;

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
