<?php

namespace Labstag\Tests;

use Generator;
use Labstag\Entity\User;

trait LoginTrait
{

    protected $clientDefault = null;

    protected $client = [];

    protected $groupes = [
        'admin',
        'superadmin',
        'disable',
        'visitor',
    ];

    /**
     * Connect user.
     *
     * @param string $name name user
     */
    protected function logIn($name)
    {
        if (is_null($this->clientDefault)) {
            $this->clientDefault = self::createClient();
        }

        if (isset($this->client[$name])) {
            return $this->client[$name];
        }

        $client        = $this->clientDefault;
        $container     = $client->getContainer();
        $doctrine      = $container->get('doctrine');
        $entityManager = $doctrine->getManager();
        $repository    = $entityManager->getRepository(User::class);
        $user          = $repository->findUserEnable($name);
        if ($user instanceof User) {
            $client->loginUser($user);
        }

        $this->client[$name] = $client;

        return $this->client[$name];
    }

    public function getGroupes(): Generator
    {
        if (!isset($this->groupes)) {
            return;
        }

        foreach ($this->groupes as $route) {
            yield [$route];
        }
    }

    public function provideAllUrlWithoutParams(): Generator
    {
        if (!isset($this->urls)) {
            return;
        }

        foreach ($this->urls as $route) {
            foreach ($this->groupes as $groupe) {
                yield [
                    $route,
                    $groupe,
                ];
            }
        }
    }
}
