<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Labstag\Entity\PhoneUser;
use Labstag\Lib\FixtureLib;

class PhoneUserFixtures extends FixtureLib implements DependentFixtureInterface
{
    /**
     * @return class-string[]
     */
    public function getDependencies(): array
    {
        return [
            DataFixtures::class,
            UserFixtures::class,
        ];
    }

    public function load(ObjectManager $objectManager): void
    {
        unset($objectManager);
        $this->loadForeach(self::NUMBER_PHONE, 'addPhone');
    }

    protected function addPhone(
        Generator $generator,
        int $index,
        array $states
    ): void
    {
        $users     = $this->installService->getData('user');
        $indexUser = $generator->numberBetween(0, (is_countable($users) ? count($users) : 0) - 1);
        $user      = $this->getReference('user_'.$indexUser);
        $number    = $generator->e164PhoneNumber;
        $phoneUser     = new PhoneUser();
        $old       = clone $phoneUser;
        $phoneUser->setRefuser($user);
        $phoneUser->setNumero($number);
        $phoneUser->setType($generator->word());
        $phoneUser->setCountry($generator->countryCode);
        $this->addReference('phone_'.$index, $phoneUser);
        $this->phoneUserRH->handle($old, $phoneUser);
        $this->phoneUserRH->changeWorkflowState($phoneUser, $states);
    }

    /**
     * @return array<int, mixed[]>
     */
    protected function getStatePhone(): array
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
