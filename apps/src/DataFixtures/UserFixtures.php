<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Labstag\Entity\User;
use Labstag\Lib\FixtureLib;

class UserFixtures extends FixtureLib implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            DataFixtures::class,
            GroupFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        unset($manager);
        $faker   = $this->setFaker();
        $users   = $this->installService->getData('user');
        $groupes = $this->groupeRepository->findAll();
        foreach ($users as $index => $user) {
            $this->addUser($groupes, $index, $user, $faker);
        }
    }

    protected function addUser(
        array $groupes,
        int $index,
        array $dataUser,
        Generator $faker
    ): void
    {
        $user = $this->userService->create($groupes, $dataUser);
        $this->upload($user, $faker);
        $this->addReference('user_'.$index, $user);
    }
}
