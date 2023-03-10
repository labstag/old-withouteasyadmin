<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Labstag\Lib\FixtureLib;

class UserFixtures extends FixtureLib implements DependentFixtureInterface
{
    /**
     * @return class-string[]
     */
    public function getDependencies(): array
    {
        return [
            DataFixtures::class,
            GroupFixtures::class,
        ];
    }

    public function load(ObjectManager $objectManager): void
    {
        unset($objectManager);
        $generator = $this->setFaker();
        $users     = $this->installService->getData('user');
        $groupes   = $this->groupeRepository->findAll();
        foreach ($users as $index => $user) {
            $this->addUser($groupes, $index, $user, $generator);
        }
    }

    protected function addUser(
        array $groupes,
        int $index,
        array $dataUser,
        Generator $generator
    ): void {
        $user = $this->userService->create($groupes, $dataUser);
        $this->upload($user, $generator);
        $this->addReference('user_'.$index, $user);
    }
}
