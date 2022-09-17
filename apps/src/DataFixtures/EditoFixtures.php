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
        $this->loadForeach(self::NUMBER_EDITO, 'addEdito');
    }

    protected function addEdito(
        Generator $generator,
        int $index,
        array $states
    ): void
    {
        $users  = $this->userRepository->findAll();
        $edito  = new Edito();
        $old    = clone $edito;
        $random = $generator->numberBetween(5, 50);
        $edito->setTitle($generator->unique()->text($random));
        // @var string $content
        $content = $generator->paragraphs(random_int(4, 10), true);
        $edito->setContent(str_replace("\n\n", "<br />\n", (string) $content));
        $edito->setPublished($generator->unique()->dateTime('now'));
        $this->addReference('edito_'.$index, $edito);
        $tabIndex = array_rand($users);
        // @var User $user
        $user = $users[$tabIndex];
        $edito->setRefuser($user);
        $this->upload($edito, $generator);
        $this->editoRH->handle($old, $edito);
        $this->editoRH->changeWorkflowState($edito, $states);
    }
}
