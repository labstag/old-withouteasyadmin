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
        'admin_memo_index',
        'admin_memo_new',
    ];

    protected $groupeDisable = [
        'visitor',
        'disable',
    ];

    /**
     * @dataProvider provideAllUrlWithoutParams
     * @param        string $route
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
            'admin_memo_show'
        );
    }

    /**
     * @dataProvider getGroupes
     */
    public function testPost($groupe)
    {
        $this->editPostRedirect(
            $groupe,
            'admin_memo_edit',
            NoteInterneType::class,
            !in_array($groupe, $this->groupeDisable)
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
            'admin_memo_new',
            NoteInterneType::class
        );
    }

    /**
     * @dataProvider getGroupes
     */
    public function testShowDataNotFound($groupe)
    {
        $this->showEditDataNotFound($groupe, 'admin_memo_show');
    }

    /**
     * @dataProvider getGroupes
     */
    public function testEditDataNotFound($groupe)
    {
        $this->showEditDataNotFound($groupe, 'admin_memo_edit');
    }

    /**
     * @dataProvider getGroupes
     */
    public function testDelete($groupe)
    {
        $this->editDelete(
            $groupe,
            !in_array($groupe, $this->groupeDisable),
            'admin_memo_delete'
        );
    }

    protected function getEntity($client)
    {
        $container     = $client->getContainer();
        $doctrine      = $container->get('doctrine');
        $entityManager = $doctrine->getManager();
        $repository    = $entityManager->getRepository(NoteInterne::class);
        /**
 * @var NoteInterneRepository $repository
*/
        $data = $repository->findOneRandom();

        return $data;
    }

    protected function getNewEntity($client)
    {
        $container     = $client->getContainer();
        $doctrine      = $container->get('doctrine');
        $entityManager = $doctrine->getManager();
        $repository    = $entityManager->getRepository(User::class);
        /**
 * @var UserRepository $repository
*/
        $user = $repository->findOneRandom();
        if (!($user instanceof User)) {
            return;
        }

        $faker = Factory::create('fr_FR');
        $memo  = new NoteInterne();
        $memo->setRefuser($user);
        $random = $faker->numberBetween(5, 50);
        $memo->setTitle($faker->unique()->text($random));
        $memo->setEnable($faker->numberBetween(0, 1));
        $maxDate   = $faker->unique()->dateTimeInInterval('now', '+30 years');
        $dateDebut = $faker->unique()->dateTime($maxDate);
        $memo->setDateDebut($dateDebut);
        $dateFin = clone $dateDebut;
        $random  = $faker->numberBetween(10, 50);
        $dateFin->modify('+' . $random . ' days');
        $random = $faker->numberBetween(2, 24);
        $dateFin->modify('+' . $random . ' hours');
        $memo->setDateFin($dateFin);
        /**
 * @var string $content
*/
        $content = $faker->unique()->paragraphs(4, true);
        $memo->setContent(str_replace("\n\n", '<br />', $content));

        return $memo;
    }
}
