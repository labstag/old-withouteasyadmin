<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Labstag\Entity\History;
use Labstag\Entity\Meta;
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
        $this->loadForeach(self::NUMBER_HISTORY, 'addHistory');
    }

    protected function addHistory(
        Generator $faker,
        int $index,
        array $states
    ): void
    {
        $users      = $this->userRepository->findAll();
        $history    = new History();
        $oldHistory = clone $history;
        $history->setName($faker->unique()->colorName);
        $meta = new Meta();
        $meta->setKeywords(implode(', ', $faker->unique()->words(random_int(4, 10))));
        $meta->setDescription($faker->unique()->sentence);
        $history->addMeta($meta);
        // @var string $content
        $content = $faker->paragraphs(random_int(2, 4), true);
        $history->setSummary(str_replace("\n\n", "<br />\n", $content));
        $users     = $this->installService->getData('user');
        $indexUser = $faker->numberBetween(0, (is_countable($users) ? count($users) : 0) - 1);
        $user      = $this->getReference('user_'.$indexUser);
        $history->setRefuser($user);
        $history->setPublished($faker->unique()->dateTime('now'));
        $this->addReference('history_'.$index, $history);
        $this->historyRH->handle($oldHistory, $history);
        $this->historyRH->changeWorkflowState($history, $states);
    }
}
