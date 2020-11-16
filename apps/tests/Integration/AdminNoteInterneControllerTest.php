<?php

namespace Labstag\Tests\Integration;

use Labstag\Entity\NoteInterne;
use Labstag\Form\Admin\NoteInterneType;
use Labstag\Repository\NoteInterneRepository;
use Labstag\Tests\IntegrationTrait;
use Labstag\Tests\LoginTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Faker\Factory;
use Labstag\Entity\User;
use Labstag\Repository\UserRepository;

class AdminNoteInterneControllerTest extends WebTestCase
{
    use LoginTrait;
    use IntegrationTrait;

    protected $urls = [
        'admin_noteinterne_index',
        'admin_noteinterne_new',
    ];

    protected $groupeDisable = [
        'visitor',
        'disable',
    ];

    /**
     * @dataProvider provideAllUrlWithoutParams
     * @param string $route
     */
    public function testUrl($route, $groupe)
    {
        $this->responseTest(
            $route,
            $groupe,
            !in_array($groupe, $this->groupeDisable)
        );
    }

    /**
     * @dataProvider getGroupes
     */
    public function testShow($groupe)
    {
        $this->showTest(
            $groupe,
            !in_array($groupe, $this->groupeDisable),
            'admin_noteinterne_show'
        );
    }

    /**
     * @dataProvider getGroupes
     */
    public function testEdit(string $groupe)
    {
        $this->editTest(
            $groupe,
            !in_array($groupe, $this->groupeDisable),
            'admin_noteinterne_edit'
        );
    }

    /**
     * @dataProvider getGroupes
     */
    public function testPost($groupe)
    {
        $this->editPost(
            $groupe,
            !in_array($groupe, $this->groupeDisable),
            'admin_noteinterne_edit',
            NoteInterneType::class
        );
    }

    /**
     * @dataProvider getGroupes
     */
    public function testAdd($groupe)
    {
        $this->addNewEntity(
            $groupe,
            !in_array($groupe, $this->groupeDisable),
            'admin_noteinterne_new',
            NoteInterneType::class
        );
    }

    /**
     * @dataProvider getGroupes
     */
    public function testShowDataNotFound($groupe)
    {
        $this->showEditDataNotFound($groupe, 'admin_noteinterne_show');
    }

    /**
     * @dataProvider getGroupes
     */
    public function testEditDataNotFound($groupe)
    {
        $this->showEditDataNotFound($groupe, 'admin_noteinterne_edit');
    }

    /**
     * @dataProvider getGroupes
     */
    public function testDelete($groupe)
    {
        $this->editDelete(
            $groupe,
            !in_array($groupe, $this->groupeDisable),
            'admin_noteinterne_delete'
        );
    }

    protected function getEntity($client)
    {
        $container     = $client->getContainer();
        $doctrine      = $container->get('doctrine');
        $entityManager = $doctrine->getManager();
        $repository    = $entityManager->getRepository(NoteInterne::class);
        /** @var NoteInterneRepository $repository */
        $data = $repository->findOneRandom();

        return $data;
    }

    protected function getNewEntity($client)
    {
        $container     = $client->getContainer();
        $doctrine      = $container->get('doctrine');
        $entityManager = $doctrine->getManager();
        $repository    = $entityManager->getRepository(User::class);
        /** @var UserRepository $repository */
        $user = $repository->findOneRandom();
        if (!($user instanceof User)) {
            return;
        }

        $faker       = Factory::create('fr_FR');
        $noteinterne = new NoteInterne();
        $noteinterne->setRefuser($user);
        $random = $faker->numberBetween(5, 50);
        $noteinterne->setTitle($faker->unique()->text($random));
        $noteinterne->setEnable($faker->numberBetween(0, 1));
        $maxDate   = $faker->unique()->dateTimeInInterval('now', '+30 years');
        $dateDebut = $faker->unique()->dateTime($maxDate);
        $noteinterne->setDateDebut($dateDebut);
        $dateFin = clone $dateDebut;
        $random  = $faker->numberBetween(10, 50);
        $dateFin->modify('+' .$random. ' days');
        $random = $faker->numberBetween(2, 24);
        $dateFin->modify('+' .$random. ' hours');
        $noteinterne->setDateFin($dateFin);
        /** @var string $content */
        $content = $faker->unique()->paragraphs(4, true);
        $noteinterne->setContent(str_replace("\n\n", '<br />', $content));

        return $noteinterne;
    }
}
