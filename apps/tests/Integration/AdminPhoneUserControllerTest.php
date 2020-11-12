<?php

namespace Labstag\Tests\Integration;

use Labstag\Entity\PhoneUser;
use Labstag\Form\Admin\PhoneUserType;
use Labstag\Repository\PhoneUserRepository;
use Labstag\Tests\IntegrationTrait;
use Labstag\Tests\LoginTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Faker\Factory;
use Labstag\Entity\User;
use Labstag\Repository\UserRepository;

class AccessRouteAdminTest extends WebTestCase
{
    use LoginTrait;
    use IntegrationTrait;

    private $urls = [
        'admin_phoneuser_index',
        'admin_phoneuser_new',
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
            'admin_phoneuser_show'
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
            'admin_phoneuser_edit'
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
            'admin_phoneuser_edit',
            PhoneUserType::class
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
            'admin_phoneuser_new',
            PhoneUserType::class
        );
    }

    /**
     * @dataProvider getGroupes
     */
    public function testShowDataNotFound($groupe)
    {
        $this->showEditDataNotFound($groupe, 'admin_phoneuser_show');
    }

    /**
     * @dataProvider getGroupes
     */
    public function testEditDataNotFound($groupe)
    {
        $this->showEditDataNotFound($groupe, 'admin_phoneuser_edit');
    }

    /**
     * @dataProvider getGroupes
     */
    public function testDelete($groupe)
    {
        $this->editDelete(
            $groupe,
            !in_array($groupe, $this->groupeDisable),
            'admin_phoneuser_delete'
        );
    }

    private function getEntity($client)
    {
        $container     = $client->getContainer();
        $doctrine      = $container->get('doctrine');
        $entityManager = $doctrine->getManager();
        $repository    = $entityManager->getRepository(PhoneUser::class);
        /** @var PhoneUserRepository $repository */
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
        $phone = new PhoneUser();
        $phone->setRefuser($user);
        $number = $faker->unique()->e164PhoneNumber();
        $phone->setNumero($number);
        $phone->setType($faker->unique()->word());
        $phone->setCountry($faker->unique()->countryCode());
        $phone->setPrincipal(rand(0, 1));

        return $phone;
    }
}
