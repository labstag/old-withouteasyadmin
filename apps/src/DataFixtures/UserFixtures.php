<?php

namespace Labstag\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Labstag\Lib\FixtureLib;
use Labstag\Repository\GroupeRepository;
use Labstag\Repository\UserRepository;
use Psr\EventDispatcher\EventDispatcherInterface;

class UserFixtures extends FixtureLib implements DependentFixtureInterface
{
    private function getUsers(): array
    {
        $users = [
            [
                'username' => 'admin',
                'password' => 'password',
                'state'    => 'valider',
                'enable'   => true,
                'verif'    => true,
                'lost'     => false,
                'email'    => 'admin@email.fr',
                'groupe'   => 'admin',
            ],
            [
                'username' => 'superadmin',
                'password' => 'password',
                'state'    => 'valider',
                'enable'   => true,
                'verif'    => true,
                'lost'     => false,
                'email'    => 'superadmin@email.fr',
                'groupe'   => 'superadmin',
            ],
            [
                'username' => 'lost',
                'password' => 'password',
                'state'    => 'lostpassword',
                'enable'   => false,
                'verif'    => true,
                'lost'     => true,
                'email'    => 'lost@email.fr',
                'groupe'   => 'user',
            ],
            [
                'username' => 'disable',
                'password' => 'password',
                'state'    => 'disable',
                'enable'   => false,
                'verif'    => true,
                'lost'     => false,
                'email'    => 'disable@email.fr',
                'groupe'   => 'user',
            ],
            [
                'username' => 'unverif',
                'password' => 'password',
                'state'    => 'avalider',
                'enable'   => false,
                'verif'    => false,
                'lost'     => false,
                'email'    => 'unverif@email.fr',
                'groupe'   => 'user',
            ],
        ];

        return $users;
    }

    public function load(ObjectManager $manager): void
    {
        unset($manager);
        $users   = $this->getUsers();
        $groupes = $this->groupeRepository->findAll();
        foreach ($users as $index => $user) {
            $this->addUser($groupes, $index, $user);
        }
    }

    public function getDependencies()
    {
        return [
            CacheFixtures::class,
            GroupFixtures::class,
        ];
    }
}
