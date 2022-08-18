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
        return $this->getDependenciesBookmarkPost();
    }

    public function load(ObjectManager $manager): void
    {
        unset($manager);
        $this->loadForeach(self::NUMBER_POST, 'addPost');
    }

    protected function addPost(
        Generator $faker,
        int $index,
        array $states
    ): void
    {
        $post    = new Post();
        $oldPost = clone $post;
        $post->setTitle($faker->unique()->colorName());
        // @var string $content
        $content = $faker->paragraphs(random_int(4, 10), true);
        $post->setContent(str_replace("\n\n", "<br />\n", $content));
        $users     = $this->installService->getData('user');
        $indexUser = $faker->numberBetween(0, (is_countable($users) ? count($users) : 0) - 1);
        $user      = $this->getReference('user_'.$indexUser);
        $post->setRefuser($user);
        $post->setPublished($faker->unique()->dateTime('now'));
        $this->setLibelles($faker, $post);
        $indexLibelle = $faker->numberBetween(0, self::NUMBER_CATEGORY - 1);
        $category     = $this->getReference('category_'.$indexLibelle);
        $post->setRefcategory($category);
        $post->setRemark((bool) random_int(0, 1));
        $this->upload($post, $faker);
        $this->addReference('post_'.$index, $post);
        $this->postRH->handle($oldPost, $post);
        $this->postRH->changeWorkflowState($post, $states);
    }
}
