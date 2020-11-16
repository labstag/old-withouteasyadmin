<?php

namespace Labstag\Tests\Integration;

use Labstag\Entity\Edito;
use Labstag\Form\Admin\EditoType;
use Labstag\Repository\EditoRepository;
use Labstag\Tests\IntegrationTrait;
use Labstag\Tests\LoginTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Faker\Factory;
use Labstag\Entity\User;
use Labstag\Repository\UserRepository;

class AdminEditoControllerTest extends WebTestCase
{
    use LoginTrait;
    use IntegrationTrait;

    protected $urls = [
        'admin_edito_index',
        'admin_edito_new',
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
            'admin_edito_show'
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
            'admin_edito_edit'
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
            'admin_edito_edit',
            EditoType::class
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
            'admin_edito_new',
            EditoType::class
        );
    }

    /**
     * @dataProvider getGroupes
     */
    public function testShowDataNotFound($groupe)
    {
        $this->showEditDataNotFound($groupe, 'admin_edito_show');
    }

    /**
     * @dataProvider getGroupes
     */
    public function testEditDataNotFound($groupe)
    {
        $this->showEditDataNotFound($groupe, 'admin_edito_edit');
    }

    /**
     * @dataProvider getGroupes
     */
    public function testDelete($groupe)
    {
        $this->editDelete(
            $groupe,
            !in_array($groupe, $this->groupeDisable),
            'admin_edito_delete'
        );
    }

    protected function getEntity($client)
    {
        $container     = $client->getContainer();
        $doctrine      = $container->get('doctrine');
        $entityManager = $doctrine->getManager();
        $repository    = $entityManager->getRepository(Edito::class);
        /** @var EditoRepository $repository */
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

        $faker = Factory::create('fr_FR');
        $edito = new Edito();
        $edito->setRefuser($user);
        $edito->setTitle($faker->unique()->text($faker->numberBetween(5, 50)));
        $edito->setEnable($faker->numberBetween(0, 1));
        /** @var string $content */
        $content = $faker->unique()->paragraphs(4, true);
        $edito->setContent(str_replace("\n\n", '<br />', $content));

        return $edito;
    }
}
