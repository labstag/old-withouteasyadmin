<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Labstag\Entity\Bookmark;
use Labstag\Lib\FixtureLib;

class BookmarkFixtures extends FixtureLib implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            DataFixtures::class,
            UserFixtures::class,
            LibelleFixtures::class,
            CategoryFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        unset($manager);
        $faker     = $this->setFaker();
        $statesTab = $this->getStates();
        for ($index = 0; $index < self::NUMBER_BOOKMARK; ++$index) {
            $stateId = array_rand($statesTab);
            $states  = $statesTab[$stateId];
            $this->addLink($faker, $states);
        }
    }

    protected function addLink(
        Generator $faker,
        array $states
    ): void {
        $bookmark = new Bookmark();
        $old      = clone $bookmark;
        if (1 == rand(0, 1)) {
            $nbr = $faker->numberBetween(0, self::NUMBER_LIBELLE - 1);
            for ($i = 0; $i < $nbr; ++$i) {
                $indexLibelle = $faker->numberBetween(0, self::NUMBER_LIBELLE - 1);
                $libelle      = $this->getReference('libelle_'.$indexLibelle);
                $bookmark->addLibelle($libelle);
            }
        }

        $users     = $this->installService->getData('user');
        $indexUser = $faker->numberBetween(0, count($users) - 1);
        $user      = $this->getReference('user_'.$indexUser);
        $bookmark->setRefuser($user);
        // @var string $content
        $content = $faker->paragraphs(rand(4, 10), true);
        $bookmark->setContent(str_replace("\n\n", "<br />\n", $content));
        $bookmark->setMetaKeywords(implode(', ', $faker->unique()->words(rand(4, 10))));
        $bookmark->setMetaDescription($faker->unique()->sentence);
        $indexLibelle = $faker->numberBetween(0, self::NUMBER_CATEGORY - 1);
        $category     = $this->getReference('category_'.$indexLibelle);
        $bookmark->setRefcategory($category);
        $bookmark->setName($faker->word());
        $bookmark->setUrl($faker->url);
        $bookmark->setPublished($faker->unique()->dateTime('now'));
        $this->upload($bookmark, $faker);
        $this->bookmarkRH->handle($old, $bookmark);
        $this->bookmarkRH->changeWorkflowState($bookmark, $states);
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
                'rejete',
            ],
        ];
    }
}
