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
    ): void {
        $user = new User();
        $old  = clone $user;

        $user->setRefgroupe($this->getRefgroupe($groupes, $dataUser['groupe']));
        $user->setUsername($dataUser['username']);
        $user->setPlainPassword($dataUser['password']);
        $user->setEmail($dataUser['email']);
        $this->upload($user, $faker);
        $this->addReference('user_'.$index, $user);
        $this->userRH->handle($old, $user);
        $this->userRH->changeWorkflowState($user, $dataUser['state']);
    }
}
