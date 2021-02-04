<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Labstag\Lib\FixtureLib;

class UserFixtures extends FixtureLib implements DependentFixtureInterface
{
    protected function getUsers(): array
    {
        $users = [
            [
                'username' => 'admin',
                'password' => 'password',
                'state'    => [
                    'submit',
                    'validation',
                ],
                'email'    => 'admin@email.fr',
                'groupe'   => 'admin',
            ],
            [
                'username' => 'superadmin',
                'password' => 'password',
                'state'    => [
                    'submit',
                    'validation',
                ],
                'email'    => 'superadmin@email.fr',
                'groupe'   => 'superadmin',
            ],
            [
                'username' => 'lost',
                'password' => 'password',
                'state'    => [
                    'submit',
                    'validation',
                    'passwordlost',
                ],
                'email'    => 'lost@email.fr',
                'groupe'   => 'user',
            ],
            [
                'username' => 'disable',
                'password' => 'password',
                'state'    => ['submit'],
                'email'    => 'disable@email.fr',
                'groupe'   => 'user',
            ],
            [
                'username' => 'unverif',
                'password' => 'password',
                'state'    => ['submit'],
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
            DataFixtures::class,
            GroupFixtures::class,
        ];
    }
}
