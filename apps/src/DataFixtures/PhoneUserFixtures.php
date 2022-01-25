<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Labstag\Entity\PhoneUser;
use Labstag\Lib\FixtureLib;

class PhoneUserFixtures extends FixtureLib implements DependentFixtureInterface
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
        $this->loadForeach(self::NUMBER_PHONE, 'addPhone');
    }

    protected function addPhone(
        Generator $faker,
        int $index,
        array $states
    ): void
    {
        $users     = $this->installService->getData('user');
        $indexUser = $faker->numberBetween(0, (is_countable($users) ? count($users) : 0) - 1);
        $user      = $this->getReference('user_'.$indexUser);
        $number    = $faker->e164PhoneNumber;
        $phone     = new PhoneUser();
        $old       = clone $phone;
        $phone->setRefuser($user);
        $phone->setNumero($number);
        $phone->setType($faker->word());
        $phone->setCountry($faker->countryCode);
        $this->addReference('phone_'.$index, $phone);
        $this->phoneUserRH->handle($old, $phone);
        $this->phoneUserRH->changeWorkflowState($phone, $states);
    }

    protected function getStatePhone()
    {
        return [
            ['submit'],
            [
                'submit',
                'valider',
            ],
        ];
    }
}
