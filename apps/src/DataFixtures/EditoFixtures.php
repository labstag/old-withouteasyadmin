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
        $this->loadForeach(self::NUMBER_EDITO, 'addEdito');
    }

    protected function addEdito(
        Generator $faker,
        int $index,
        array $states
    ): void
    {
        $users  = $this->userRepository->findAll();
        $edito  = new Edito();
        $old    = clone $edito;
        $random = $faker->numberBetween(5, 50);
        $edito->setTitle($faker->unique()->text($random));
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
