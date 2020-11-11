<?php

namespace Labstag\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Labstag\Repository\UserRepository;
use Faker\Factory;
use Faker\Generator;
use Labstag\Entity\NoteInterne;
use Labstag\Entity\User;


/**
 * @codeCoverageIgnore
 */
class NoteInterneFixtures extends Fixture implements DependentFixtureInterface
{
    const NUMBER = 25;

    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function load(ObjectManager $manager)
    {
        $users = $this->userRepository->findAll();
        $faker = Factory::create('fr_FR');
        /** @var resource $finfo */
        $maxDate = $faker->unique()->dateTimeInInterval('now', '+30 years');
        for ($index = 0; $index < self::NUMBER; ++$index) {
            $this->addNoteInterne($users, $faker, $manager, $maxDate);
        }

        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }

    private function addNoteInterne(
        $users,
        Generator $faker,
        ObjectManager $manager,
        $maxDate
    ): void
    {
        $noteinterne = new NoteInterne();
        $noteinterne->setTitle($faker->unique()->text(rand(5, 50)));
        $noteinterne->setEnable((bool) rand(0, 1));
        $dateDebut = $faker->unique()->dateTime($maxDate);
        $noteinterne->setDateDebut($dateDebut);
        $dateFin = clone $dateDebut;
        $dateFin->modify('+' .rand(10, 50). ' days');
        $dateFin->modify('+' .rand(2, 24). ' hours');
        $noteinterne->setDateFin($dateFin);
        /** @var string $content */
        $content = $faker->unique()->paragraphs(4, true);
        $noteinterne->setContent(str_replace("\n\n", '<br />', $content));
        $tabIndex = array_rand($users);
        /** @var User $user */
        $user = $users[$tabIndex];
        $noteinterne->setRefuser($user);
        $manager->persist($noteinterne);
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
