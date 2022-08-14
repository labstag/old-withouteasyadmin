<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Labstag\Entity\Chapter;
use Labstag\Lib\FixtureLib;

class ChapterFixtures extends FixtureLib implements DependentFixtureInterface
{

    protected $position = [];

    public function getDependencies()
    {
        return [
            DataFixtures::class,
            UserFixtures::class,
            HistoryFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        unset($manager);
        $this->loadForeach(self::NUMBER_HISTORY, 'addChapter');
    }

    protected function addChapter(
        Generator $faker,
        int $index,
        array $states
    ): void
    {
        $chapter    = new Chapter();
        $oldChapter = clone $chapter;
        $chapter->setName($faker->unique()->colorName);
        // @var string $content
        $content = $faker->paragraphs(random_int(4, 10), true);
        $chapter->setContent(str_replace("\n\n", "<br />\n", $content));
        $indexHistory = $faker->numberBetween(0, self::NUMBER_HISTORY - 1);
        $history      = $this->getReference('history_'.$indexHistory);
        if (!isset($this->position[$indexHistory])) {
            $this->position[$indexHistory] = [];
        }

        $chapter->setRefhistory($history);
        $chapter->setPublished($faker->unique()->dateTime('now'));
        $indexposition = $this->position[$indexHistory];
        $position      = is_countable($indexposition) ? count($indexposition) : 0;
        $chapter->setPosition($position + 1);
        $this->addReference('chapter_'.$index, $chapter);
        $this->position[$indexHistory][] = $chapter;
        $this->chapterRH->handle($oldChapter, $chapter);
        $this->chapterRH->changeWorkflowState($chapter, $states);
    }
}
