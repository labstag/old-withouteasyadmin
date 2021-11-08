<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Labstag\Entity\Chapter;
use Labstag\Lib\FixtureLib;

class ChapterFixtures extends FixtureLib implements DependentFixtureInterface
{
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
        $users = $this->userRepository->findAll();
        $faker = $this->setFaker();
        // @var resource $finfo
        $statesTab = $this->getStates();
        for ($index = 0; $index < self::NUMBER_HISTORY; ++$index) {
            $stateId = array_rand($statesTab);
            $states  = $statesTab[$stateId];
            $this->addChapter($users, $faker, $index, $states);
        }
    }

    protected function addChapter(
        array $users,
        Generator $faker,
        int $index,
        array $states
    ): void {
        $chapter    = new Chapter();
        $oldChapter = clone $chapter;
        $chapter->setName($faker->unique()->colorName);
        $chapter->setMetaKeywords(implode(', ', $faker->unique()->words(rand(4, 10))));
        $chapter->setMetaDescription($faker->unique()->sentence);
        // @var string $content
        $content = $faker->paragraphs(rand(4, 10), true);
        $chapter->setContent(str_replace("\n\n", "<br />\n", $content));
        $indexHistory = $faker->numberBetween(0, self::NUMBER_HISTORY - 1);
        $history      = $this->getReference('history_'.$indexHistory);
        $chapter->setRefhistory($history);
        $chapter->setPublished($faker->unique()->dateTime('now'));
        $this->addReference('chapter_'.$index, $chapter);
        $this->chapterRH->handle($oldChapter, $chapter);
        $this->chapterRH->changeWorkflowState($chapter, $states);
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
