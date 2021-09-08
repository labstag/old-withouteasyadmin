<?php

namespace Labstag\Tests\Integration;

use Labstag\Entity\AdresseUser;
use Labstag\Entity\User;
use Labstag\Repository\AdresseUserRepository;
use Labstag\Repository\UserRepository;
use Labstag\Tests\IntegrationTrait;
use Labstag\Tests\LoginTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Faker\Factory;
use Labstag\Form\Admin\AdresseUserType;

class AdminAdresseUserControllerTest extends WebTestCase
{
    use LoginTrait;
    use IntegrationTrait;

    protected $urls = [
        'admin_adresseuser_index',
        'admin_adresseuser_new',
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
            'admin_adresseuser_show'
        );
    }

    /**
     * @dataProvider getGroupes
     */
    public function testPost($groupe)
    {
        $this->editPostRedirect(
            $groupe,
            'admin_adresseuser_edit',
            AdresseUserType::class,
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
            'admin_adresseuser_new',
            AdresseUserType::class
        );
    }

    /**
     * @dataProvider getGroupes
     */
    public function testShowDataNotFound($groupe)
    {
        $this->showEditDataNotFound($groupe, 'admin_adresseuser_show');
    }

    /**
     * @dataProvider getGroupes
     */
    public function testEditDataNotFound($groupe)
    {
        $this->showEditDataNotFound($groupe, 'admin_adresseuser_edit');
    }

    /**
     * @dataProvider getGroupes
     */
    public function testDelete($groupe)
    {
        $this->editDelete(
            $groupe,
            !in_array($groupe, $this->groupeDisable),
            'admin_adresseuser_delete'
        );
    }

    protected function getEntity($client)
    {
        $container     = $client->getContainer();
        $doctrine      = $container->get('doctrine');
        $entityManager = $doctrine->getManager();
        $repository    = $entityManager->getRepository(AdresseUser::class);
        /**
 * @var AdresseUserRepository $repository
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

        $faker   = Factory::create('fr_FR');
        $adresse = new AdresseUser();
        $adresse->setRefuser($user);
        $adresse->setRue($faker->unique()->streetAddress());
        $adresse->setVille($faker->unique()->city());
        $adresse->setCountry($faker->unique()->countryCode());
        $adresse->setZipcode($faker->unique()->postcode());
        $adresse->setType($faker->unique()->colorName);
        $latitude  = $faker->unique()->latitude();
        $longitude = $faker->unique()->longitude();
        $gps       = $latitude . ',' . $longitude;
        $adresse->setGps($gps);
        $adresse->setPmr($faker->numberBetween(0, 1));

        return $adresse;
    }
}
