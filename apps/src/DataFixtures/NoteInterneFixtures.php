<?php

namespace Labstag\DataFixtures;

use DateTime;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Labstag\Entity\NoteInterne;
use Labstag\Lib\FixtureLib;

class NoteInterneFixtures extends FixtureLib implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [
            DataFixtures::class,
            UserFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        unset($manager);
        $users     = $this->userRepository->findAll();
        $faker     = $this->setFaker();
        $statesTab = $this->getStates();
        $maxDate   = $faker->unique()->dateTimeInInterval('now', '+30 years');
        for ($index = 0; $index < self::NUMBER_NOTEINTERNE; ++$index) {
            $stateId = array_rand($statesTab);
            $states  = $statesTab[$stateId];
            $this->addNoteInterne($users, $faker, $index, $maxDate, $states);
        }
    }

    protected function addNoteInterne(
        array $users,
        Generator $faker,
        int $index,
        DateTime $maxDate,
        array $states
    ): void
    {
        $noteinterne = new NoteInterne();
        $old         = clone $noteinterne;
        $random      = $faker->numberBetween(5, 50);
        $noteinterne->setTitle($faker->unique()->text($random));
        $dateDebut = $faker->dateTime($maxDate);
        $noteinterne->setDateDebut($dateDebut);
        $dateFin = clone $dateDebut;
        $dateFin->modify('+'.$faker->numberBetween(10, 50).' days');
        $dateFin->modify('+'.$faker->numberBetween(2, 24).' hours');
        $noteinterne->setDateFin($dateFin);
        // @var string $content
        $content = $faker->paragraphs(4, true);
        $noteinterne->setContent(str_replace("\n\n", "<br />\n", $content));
        $this->addReference('noteinterne_'.$index, $noteinterne);
        $tabIndex = array_rand($users);
        // @var User $user
        $user = $users[$tabIndex];
        $noteinterne->setRefuser($user);
        $this->upload($noteinterne, $faker);
        $this->noteInterneRH->handle($old, $noteinterne);
        $this->noteInterneRH->changeWorkflowState($noteinterne, $states);
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
