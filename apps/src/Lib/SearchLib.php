<?php

namespace Labstag\Lib;

use DateTime;
use Labstag\Entity\Category;
use Labstag\Entity\Groupe;
use Labstag\Entity\User;
use Labstag\Repository\CategoryRepository;
use Labstag\Repository\GroupeRepository;
use Labstag\Repository\UserRepository;
use Labstag\Service\RepositoryService;

abstract class SearchLib
{

    public int $limit = 10;

    public int $page = 0;

    public function search(
        array $get,
        RepositoryService $repositoryService
    ): void
    {
        /** @var UserRepository $userRepository */
        $userRepository = $repositoryService->get(User::class);
        /** @var CategoryRepository $categoryRepository */
        $categoryRepository = $repositoryService->get(Category::class);
        /** @var GroupeRepository $groupeRepository */
        $groupeRepository = $repositoryService->get(Groupe::class);
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
                'user'     => $userRepository->find($value),
                'category' => $categoryRepository->find($value),
                'groupe'   => $groupeRepository->find($value),
                default    => $value
            };
        }
    }
}
