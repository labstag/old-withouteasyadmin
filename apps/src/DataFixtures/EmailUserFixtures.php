<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Labstag\Entity\EmailUser;
use Labstag\Entity\User;
use Labstag\Lib\FixtureLib;

class EmailUserFixtures extends FixtureLib implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            DataFixtures::class,
            UserFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        unset($manager);
        $faker = $this->setFaker();
        $users = $this->installService->getData('user');
        for ($index = 0; $index < self::NUMBER_EMAIL; ++$index) {
            $indexUser = $faker->numberBetween(0, (is_countable($users) ? count($users) : 0) - 1);
            $user      = $this->getReference('user_'.$indexUser);
            $this->addEmail($faker, $user);
        }
    }

    protected function addEmail(
        Generator $faker,
        User $user
    ): void
    {
        $email = new EmailUser();
        $old   = clone $email;
        $email->setRefuser($user);
        $email->setAddress($faker->safeEmail);
        $this->emailUserRH->handle($old, $email);
    }
}
