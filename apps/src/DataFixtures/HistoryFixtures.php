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
        $this->loadForeach(self::NUMBER_HISTORY, 'addHistory');
    }

    protected function addHistory(
        Generator $generator,
        int $index,
        array $states
    ): void
    {
        $users = $this->userRepository->findAll();
        $history = new History();
        $meta = new Meta();
        $meta->setHistory($history);
        $this->setMeta($meta);
        $oldHistory = clone $history;
        $history->setName($generator->unique()->colorName());
        /** @var  string $content */
        $content = $generator->paragraphs(random_int(2, 4), true);
        $history->setSummary(str_replace("\n\n", "<br />\n", (string) $content));
        $users = $this->installService->getData('user');
        $indexUser = $generator->numberBetween(0, (is_countable($users) ? count($users) : 0) - 1);
        $user = $this->getReference('user_'.$indexUser);
        $history->setRefuser($user);
        $history->setPublished($generator->unique()->dateTime('now'));
        $this->addReference('history_'.$index, $history);
        $this->historyRequestHandler->handle($oldHistory, $history);
        $this->historyRequestHandler->changeWorkflowState($history, $states);
    }
}
