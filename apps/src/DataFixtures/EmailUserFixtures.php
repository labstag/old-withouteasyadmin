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
    public function getDependencies(): array
    {
        return [
            DataFixtures::class,
            UserFixtures::class,
        ];
    }

    public function load(ObjectManager $objectManager): void
    {
        $generator = $this->setFaker();
        $users     = $this->installService->getData('user');
        for ($index = 0; $index < self::NUMBER_EMAIL; ++$index) {
            $indexUser = $generator->numberBetween(0, (is_countable($users) ? count($users) : 0) - 1);
            /** @var User $user */
            $user = $this->getReference('user_'.$indexUser);
            $this->addEmail($generator, $user, $objectManager);
        }

        $objectManager->flush();
    }

    protected function addEmail(
        Generator $generator,
        User $user,
        ObjectManager $objectManager
    ): void {
        $emailUser = new EmailUser();
        $emailUser->setRefuser($user);
        $emailUser->setAddress($generator->safeEmail);

        $objectManager->persist($emailUser);
    }
}
