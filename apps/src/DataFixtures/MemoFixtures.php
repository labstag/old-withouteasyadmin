<?php

namespace Labstag\DataFixtures;

use DateTime;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Labstag\Entity\Memo;
use Labstag\Lib\FixtureLib;

class MemoFixtures extends FixtureLib implements DependentFixtureInterface
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
        $users     = $this->userRepository->findAll();
        $faker     = $this->setFaker();
        $statesTab = $this->getStatesData();
        $maxDate   = $faker->unique()->dateTimeInInterval('now', '+30 years');
        for ($index = 0; $index < self::NUMBER_NOTEINTERNE; ++$index) {
            $stateId = array_rand($statesTab);
            $states  = $statesTab[$stateId];
            $this->addMemo($users, $faker, $index, $maxDate, $states);
        }
    }

    protected function addMemo(
        array $users,
        Generator $faker,
        int $index,
        DateTime $maxDate,
        array $states
    ): void
    {
        $memo   = new Memo();
        $old    = clone $memo;
        $random = $faker->numberBetween(5, 50);
        $memo->setTitle($faker->unique()->text($random));
        $dateStart = $faker->dateTime($maxDate);
        $memo->setDateStart($dateStart);
        $dateEnd = clone $dateStart;
        $dateEnd->modify('+'.$faker->numberBetween(10, 50).' days');
        $dateEnd->modify('+'.$faker->numberBetween(2, 24).' hours');
        $memo->setDateEnd($dateEnd);
        // @var string $content
        $content = $faker->paragraphs(4, true);
        $memo->setContent(str_replace("\n\n", "<br />\n", $content));
        $this->addReference('memo_'.$index, $memo);
        $tabIndex = array_rand($users);
        // @var User $user
        $user = $users[$tabIndex];
        $memo->setRefuser($user);
        $this->upload($memo, $faker);
        $this->noteInterneRH->handle($old, $memo);
        $this->noteInterneRH->changeWorkflowState($memo, $states);
    }
}
