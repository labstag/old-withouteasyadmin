<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Labstag\Entity\Post;
use Labstag\Lib\FixtureLib;

class PostFixtures extends FixtureLib implements DependentFixtureInterface
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
        $this->add($manager);
    }

    protected function add(ObjectManager $manager): void
    {
        unset($manager);
        $faker     = $this->setFaker();
        $statesTab = $this->getStates();
        for ($index = 0; $index < self::NUMBER_POST; ++$index) {
            $stateId = array_rand($statesTab);
            $states  = $statesTab[$stateId];
            $this->addPost($faker, $index, $states);
        }
    }

    protected function addPost(
        Generator $faker,
        int $index,
        array $states
    ): void {
        $post    = new Post();
        $oldPost = clone $post;
        $post->setTitle($faker->unique()->colorName);
        $post->setMetaKeywords(implode(', ', $faker->unique()->words(rand(4, 10))));
        $post->setMetaDescription($faker->unique()->sentence);
        // @var string $content
        $content = $faker->paragraphs(rand(4, 10), true);
        $post->setContent(str_replace("\n\n", "<br />\n", $content));
        $users     = $this->installService->getData('user');
        $indexUser = $faker->numberBetween(0, count($users) - 1);
        $user      = $this->getReference('user_'.$indexUser);
        $post->setRefuser($user);
        $post->setPublished($faker->unique()->dateTime('now'));
        if (1 == rand(0, 1)) {
            $nbr = $faker->numberBetween(0, self::NUMBER_LIBELLE - 1);
            for ($i = 0; $i < $nbr; ++$i) {
                $indexLibelle = $faker->numberBetween(0, self::NUMBER_LIBELLE - 1);
                $libelle      = $this->getReference('libelle_'.$indexLibelle);
                $post->addLibelle($libelle);
            }
        }

        $indexLibelle = $faker->numberBetween(0, self::NUMBER_CATEGORY - 1);
        $category     = $this->getReference('category_'.$indexLibelle);
        $post->setRefcategory($category);
        $post->setCommentaire((bool) rand(0, 1));
        $this->upload($post, $faker);
        $this->addReference('post_'.$index, $post);
        $this->templateRH->handle($oldPost, $post);
        $this->postRH->changeWorkflowState($post, $states);
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
