<?php

namespace Labstag\DataFixtures;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Labstag\Lib\FixtureLib;
use Labstag\Repository\GroupeRepository;


/**
 * @codeCoverageIgnore
 */
class UserFixtures extends FixtureLib implements DependentFixtureInterface
{

    private GroupeRepository $groupeRepository;

    public function __construct(GroupeRepository $groupeRepository)
    {
        $this->groupeRepository = $groupeRepository;
    }

    private function getUsers(): array
    {
        $users = [
            [
                'username' => 'admin',
                'password' => 'password',
                'enable'   => true,
                'verif'    => true,
                'email'    => 'admin@email.fr',
                'groupe'   => 'admin',
            ],
            [
                'username' => 'superadmin',
                'password' => 'password',
                'enable'   => true,
                'verif'    => true,
                'email'    => 'superadmin@email.fr',
                'groupe'   => 'superadmin',
            ],
            [
                'username' => 'disable',
                'password' => 'password',
                'enable'   => false,
                'verif'    => true,
                'email'    => 'disable@email.fr',
                'groupe'   => 'user',
            ],
            [
                'username' => 'unverif',
                'password' => 'password',
                'enable'   => false,
                'verif'    => false,
                'email'    => 'unverif@email.fr',
                'groupe'   => 'user',
            ],
        ];

        return $users;
    }

    public function load(ObjectManager $manager)
    {
        $users   = $this->getUsers();
        $groupes = $this->groupeRepository->findAll();
        foreach ($users as $index => $user) {
            $this->addUser($groupes, $index, $user, $manager);
        }
    }

    public function getDependencies()
    {
        return [
            GroupFixtures::class,
        ];
    }
}
