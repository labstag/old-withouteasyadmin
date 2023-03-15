<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Labstag\Entity\User;
use Labstag\Lib\FixtureLib;

class UserFixtures extends FixtureLib implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [
            DataFixtures::class,
            GroupFixtures::class,
        ];
    }

    public function load(ObjectManager $objectManager): void
    {
        $generator = $this->setFaker();
        $users     = $this->installService->getData('user');
        $groupes   = $this->groupeRepository->findAll();
        foreach ($users as $index => $user) {
            $this->addUser($groupes, $index, $user, $generator, $objectManager);
        }

        $objectManager->flush();
    }

    protected function addUser(
        array $groupes,
        int $index,
        array $dataUser,
        Generator $generator,
        ObjectManager $objectManager
    ): void
    {
        $user = new User();

        $user->setRefgroupe($this->getRefgroupe($groupes, $dataUser['groupe']));
        $user->setUsername($dataUser['username']);
        $user->setPlainPassword($dataUser['password']);
        $user->setEmail($dataUser['email']);

        $objectManager->persist($user);
        $this->workflowService->changeState($user, $dataUser['state']);
        $this->upload($user, $generator);
        $this->addReference('user_'.$index, $user);
    }
}
