<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Labstag\Entity\Memo;
use Labstag\Lib\FixtureLib;

class MemoFixtures extends FixtureLib implements DependentFixtureInterface
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
        $this->loadForeach(self::NUMBER_NOTEINTERNE, 'addMemo');
    }

    protected function addMemo(
        Generator $generator,
        int $index,
        array $states
    ): void
    {
        $dateTime = $generator->unique()->dateTimeInInterval('now', '+30 years');
        $users   = $this->userRepository->findAll();
        $memo    = new Memo();
        $old     = clone $memo;
        $random  = $generator->numberBetween(5, 50);
        $memo->setTitle($generator->unique()->text($random));
        $dateStart = $generator->dateTime($dateTime);
        $memo->setDateStart($dateStart);
        $dateEnd = clone $dateStart;
        $dateEnd->modify('+'.$generator->numberBetween(10, 50).' days');
        $dateEnd->modify('+'.$generator->numberBetween(2, 24).' hours');

        $memo->setDateEnd($dateEnd);
        // @var string $content
        $content = $generator->paragraphs(4, true);
        $memo->setContent(str_replace("\n\n", "<br />\n", (string) $content));
        $this->addReference('memo_'.$index, $memo);
        $tabIndex = array_rand($users);
        // @var User $user
        $user = $users[$tabIndex];
        $memo->setRefuser($user);
        $this->upload($memo, $generator);
        $this->memoRequestHandler->handle($old, $memo);
        $this->memoRequestHandler->changeWorkflowState($memo, $states);
    }
}
