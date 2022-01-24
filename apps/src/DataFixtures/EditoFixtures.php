<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Labstag\Entity\Edito;
use Labstag\Entity\User;
use Labstag\Lib\FixtureLib;

class EditoFixtures extends FixtureLib implements DependentFixtureInterface
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
        $users = $this->userRepository->findAll();
        $faker = $this->setFaker();
        // @var resource $finfo
        $statesTab = $this->getStatesData();
        for ($index = 0; $index < self::NUMBER_EDITO; ++$index) {
            $stateId = array_rand($statesTab);
            $states  = $statesTab[$stateId];
            $this->addEdito($users, $faker, $index, $states);
        }
    }

    protected function addEdito(
        array $users,
        Generator $faker,
        int $index,
        array $states
    ): void
    {
        $edito  = new Edito();
        $old    = clone $edito;
        $random = $faker->numberBetween(5, 50);
        $edito->setTitle($faker->unique()->text($random));
        $edito->setMetaKeywords(implode(', ', $faker->unique()->words(random_int(4, 10))));
        $edito->setMetaDescription($faker->unique()->sentence);
        // @var string $content
        $content = $faker->paragraphs(random_int(4, 10), true);
        $edito->setContent(str_replace("\n\n", "<br />\n", $content));
        $edito->setPublished($faker->unique()->dateTime('now'));
        $this->addReference('edito_'.$index, $edito);
        $tabIndex = array_rand($users);
        // @var User $user
        $user = $users[$tabIndex];
        $edito->setRefuser($user);
        $this->upload($edito, $faker);
        $this->editoRH->handle($old, $edito);
        $this->editoRH->changeWorkflowState($edito, $states);
    }
}
