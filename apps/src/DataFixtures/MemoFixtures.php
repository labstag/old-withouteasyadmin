<?php

namespace Labstag\DataFixtures;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Labstag\Entity\Memo;
use Labstag\Entity\User;
use Labstag\Lib\FixtureLib;
use Labstag\Repository\UserRepository;

class MemoFixtures extends FixtureLib implements DependentFixtureInterface
{
    public function getDependencies(): array
    {
        return [
            DataFixtures::class,
            UserFixtures::class,
        ];
    }

    public function load(ObjectManager $objectManager): void
    {
        $this->loadForeach(self::NUMBER_NOTEINTERNE, 'addMemo', $objectManager);
    }

    protected function addMemo(
        Generator $generator,
        int $index,
        array $states,
        ObjectManager $objectManager
    ): void {
        $dateTime = $generator->unique()->dateTimeInInterval('now', '+30 years');
        /** @var UserRepository $userRepository */
        $userRepository = $objectManager->getRepository(User::class);
        $users          = $userRepository->findAll();
        $memo           = new Memo();
        $random         = $generator->numberBetween(5, 50);
        $memo->setTitle($generator->unique()->text($random));
        $dateStart = $generator->dateTime($dateTime);
        $memo->setDateStart($dateStart);
        $dateEnd = clone $dateStart;
        $dateEnd->modify('+'.$generator->numberBetween(10, 50).' days');
        $dateEnd->modify('+'.$generator->numberBetween(2, 24).' hours');

        $memo->setDateEnd($dateEnd);
        /** @var string $content */
        $content = $generator->paragraphs(4, true);
        $memo->setContent(str_replace("\n\n", "<br />\n", (string) $content));
        $this->addReference('memo_'.$index, $memo);
        $tabIndex = array_rand($users);
        /** @var User $user */
        $user = $users[$tabIndex];
        $memo->setRefuser($user);
        $this->upload($memo, $generator);
        $objectManager->persist($memo);
        $this->workflowService->changeState($memo, $states);
    }
}
