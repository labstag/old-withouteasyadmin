<?php

namespace Labstag\Tests\Integration;

use Labstag\Entity\LienUser;
use Labstag\Form\Admin\LienUserType;
use Labstag\Repository\LienUserRepository;
use Labstag\Tests\IntegrationTrait;
use Labstag\Tests\LoginTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Faker\Factory;
use Labstag\Entity\User;
use Labstag\Repository\UserRepository;

class AdminLienUserControllerTest extends WebTestCase
{
    use LoginTrait;
    use IntegrationTrait;

    private $urls = [
        'admin_lienuser_index',
        'admin_lienuser_new',
    ];

    private $groupeDisable = [
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
            'admin_lienuser_show'
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
            'admin_lienuser_edit'
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
            'admin_lienuser_edit',
            LienUserType::class
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
            'admin_lienuser_new',
            LienUserType::class
        );
    }

    /**
     * @dataProvider getGroupes
     */
    public function testShowDataNotFound($groupe)
    {
        $this->showEditDataNotFound($groupe, 'admin_lienuser_show');
    }

    /**
     * @dataProvider getGroupes
     */
    public function testEditDataNotFound($groupe)
    {
        $this->showEditDataNotFound($groupe, 'admin_lienuser_edit');
    }

    /**
     * @dataProvider getGroupes
     */
    public function testDelete($groupe)
    {
        $this->editDelete(
            $groupe,
            !in_array($groupe, $this->groupeDisable),
            'admin_lienuser_delete'
        );
    }

    private function getEntity($client)
    {
        $container     = $client->getContainer();
        $doctrine      = $container->get('doctrine');
        $entityManager = $doctrine->getManager();
        $repository    = $entityManager->getRepository(LienUser::class);
        /** @var LienUserRepository $repository */
        $data = $repository->findOneRandom();

        return $data;
    }

    private function getNewEntity($client)
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

        $faker = Factory::create('fr_FR');
        $lien  = new LienUser();
        $lien->setRefuser($user);
        $lien->setName($faker->unique()->word());
        $lien->setAdresse($faker->unique->url());

        return $lien;
    }
}
