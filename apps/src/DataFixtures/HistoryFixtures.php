<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Labstag\Entity\History;
use Labstag\Lib\FixtureLib;

class HistoryFixtures extends FixtureLib implements DependentFixtureInterface
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
        $statesTab = $this->getStates();
        for ($index = 0; $index < self::NUMBER_HISTORY; ++$index) {
            $stateId = array_rand($statesTab);
            $states  = $statesTab[$stateId];
            $this->addHistory($users, $faker, $index, $states);
        }
    }

    protected function addHistory(
        array $users,
        Generator $faker,
        int $index,
        array $states
    ): void
    {
        $history    = new History();
        $oldHistory = clone $history;
        $history->setName($faker->unique()->colorName);
        $history->setMetaKeywords(implode(', ', $faker->unique()->words(rand(4, 10))));
        $history->setMetaDescription($faker->unique()->sentence);
        // @var string $content
        $content = $faker->paragraphs(rand(2, 4), true);
        $history->setSummary(str_replace("\n\n", "<br />\n", $content));
        $users     = $this->installService->getData('user');
        $indexUser = $faker->numberBetween(0, count($users) - 1);
        $user      = $this->getReference('user_'.$indexUser);
        $history->setRefuser($user);
        $history->setPublished($faker->unique()->dateTime('now'));
        $this->addReference('history_'.$index, $history);
        $this->historyRH->handle($oldHistory, $history);
        $this->historyRH->changeWorkflowState($history, $states);
    }

    protected function getStates()
    {
        return [
            ['submit'],
            [
                'submit',
                'relire',
            ],
            [
                'submit',
                'relire',
                'corriger',
            ],
            [
                'submit',
                'relire',
                'publier',
            ],
            [
                'submit',
                'relire',
                'rejeter',
            ],
        ];
    }
}