<?php

namespace Labstag\Tests\Integration;

use Labstag\Entity\Email;
use Labstag\Entity\EmailUser;
use Labstag\Entity\User;
use Labstag\Form\Security\ChangePasswordType;
use Labstag\Form\Security\DisclaimerType;
use Labstag\Form\Security\LoginType;
use Labstag\Form\Security\LostPasswordType;
use Labstag\Repository\EmailUserRepository;
use Labstag\Repository\UserRepository;
use Labstag\Tests\IntegrationTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class SecurityControllerTest extends WebTestCase
{
    use IntegrationTrait;

    public function testDisclaimer()
    {
        $client   = self::createClient();
        $router   = $client->getContainer()->get('router');
        $url      = $router->generate('disclaimer');
        $crawler  = $client->request(Request::METHOD_GET, $url);
        $response = $client->getResponse();
        $this->assertTrue($response->isSuccessful());
        $newform  = $this->createForm($client, DisclaimerType::class);
        $nameForm = $newform->getName();
        $filter   = $crawler->filter('form[name="' . $nameForm . '"]');
        $form     = $filter->form();
        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testLogin()
    {
        $client   = self::createClient();
        $router   = $client->getContainer()->get('router');
        $url      = $router->generate('app_login');
        $crawler  = $client->request(Request::METHOD_GET, $url);
        $response = $client->getResponse();
        $this->assertTrue($response->isSuccessful());
        $newform                      = $this->createForm($client, LoginType::class);
        $nameForm                     = $newform->getName();
        $filter                       = $crawler->filter('form[name="' . $nameForm . '"]');
        $form                         = $filter->form();
        $post                         = $form->getValues();
        $post[$nameForm.'[username]'] = 'test';
        $post[$nameForm.'[password]'] = 'test';
        $filter->form($post);
        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $crawler                      = $client->request(Request::METHOD_GET, $url);
        $filter                       = $crawler->filter('form[name="' . $nameForm . '"]');
        $form                         = $filter->form();
        $post                         = $form->getValues();
        $post[$nameForm.'[username]'] = 'superadmin';
        $post[$nameForm.'[password]'] = 'password';
        $filter->form($post);
        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }

    public function testLost()
    {
        $client   = self::createClient();
        $router   = $client->getContainer()->get('router');
        $url      = $router->generate('app_lost');
        $crawler  = $client->request(Request::METHOD_GET, $url);
        $response = $client->getResponse();
        $this->assertTrue($response->isSuccessful());
        $newform                   = $this->createForm($client, LostPasswordType::class);
        $nameForm                  = $newform->getName();
        $filter                    = $crawler->filter('form[name="' . $nameForm . '"]');
        $form                      = $filter->form();
        $post                      = $form->getValues();
        $post[$nameForm.'[value]'] = 'test';
        $form                      = $filter->form($post);
        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $crawler                   = $client->request(Request::METHOD_GET, $url);
        $filter                    = $crawler->filter('form[name="' . $nameForm . '"]');
        $form                      = $filter->form();
        $post                      = $form->getValues();
        $post[$nameForm.'[value]'] = 'superadmin';
        $filter->form($post);
        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);

        $crawler                   = $client->request(Request::METHOD_GET, $url);
        $filter                    = $crawler->filter('form[name="' . $nameForm . '"]');
        $form                      = $filter->form();
        $post                      = $form->getValues();
        $post[$nameForm.'[value]'] = 'superadmin@email.fr';
        $filter->form($post);
        $client->submit($form);
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }

    public function testChangePasswordLostOn()
    {
        $client        = self::createClient();
        $container     = $client->getContainer();
        $doctrine      = $container->get('doctrine');
        $entityManager = $doctrine->getManager();
        /**
 * @var UserRepository $repository
*/
        $repository = $entityManager->getRepository(User::class);
        /**
 * @var User $user
*/
        $user = $repository->findOneRandomToLost(0);
        if (!($user instanceof User)) {
            $this->markTestSkipped('test désactivé');
            return;
        }

        $router = $client->getContainer()->get('router');
        $url    = $router->generate(
            'app_changepassword',
            [
                'id' => $user->getId(),
            ]
        );
        $client->request(Request::METHOD_GET, $url);
        $response = $client->getResponse();
        $this->assertTrue($response->isRedirection());
    }

    public function testChangePasswordLostOff()
    {
        $client        = self::createClient();
        $container     = $client->getContainer();
        $doctrine      = $container->get('doctrine');
        $entityManager = $doctrine->getManager();
        /**
 * @var UserRepository $repository
*/
        $repository = $entityManager->getRepository(User::class);
        /**
 * @var User $user
*/
        $user = $repository->findOneRandomToLost(1);
        if (!($user instanceof User)) {
            $this->markTestSkipped('test désactivé');
            return;
        }

        $router   = $client->getContainer()->get('router');
        $url      = $router->generate(
            'app_changepassword',
            [
                'id' => $user->getId(),
            ]
        );
        $crawler  = $client->request(Request::METHOD_GET, $url);
        $response = $client->getResponse();
        $this->assertTrue($response->isSuccessful());
        $newform  = $this->createForm($client, ChangePasswordType::class, $user);
        $nameForm = $newform->getName();
        $filter   = $crawler->filter('form[name="' . $nameForm . '"]');
        $form     = $filter->form();
        $client->submit($form);

        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }

    public function testConfirmUserVerifOff()
    {
        $client        = self::createClient();
        $container     = $client->getContainer();
        $doctrine      = $container->get('doctrine');
        $entityManager = $doctrine->getManager();
        /**
 * @var UserRepository $repository
*/
        $repository = $entityManager->getRepository(User::class);
        /**
 * @var User $user
*/
        $user = $repository->findOneRandomToVerif(0);
        if (!($user instanceof User)) {
            $this->markTestSkipped('test désactivé');
            return;
        }

        $router = $client->getContainer()->get('router');
        $url    = $router->generate(
            'app_confirm_user',
            [
                'id' => $user->getId(),
            ]
        );
        $client->request(Request::METHOD_GET, $url);
        $response = $client->getResponse();
        $this->assertTrue($response->isRedirection());
    }

    public function testConfirmUserVerifOn()
    {
        $client        = self::createClient();
        $container     = $client->getContainer();
        $doctrine      = $container->get('doctrine');
        $entityManager = $doctrine->getManager();
        /**
 * @var UserRepository $repository
*/
        $repository = $entityManager->getRepository(User::class);
        /**
 * @var User $user
*/
        $user = $repository->findOneRandomToVerif(1);
        if (!($user instanceof User)) {
            $this->markTestSkipped('test désactivé');
            return;
        }

        $router = $client->getContainer()->get('router');
        $url    = $router->generate(
            'app_confirm_user',
            [
                'id' => $user->getId(),
            ]
        );
        $client->request(Request::METHOD_GET, $url);
        $response = $client->getResponse();
        $this->assertTrue($response->isRedirection());
    }

    public function testConfirmMailVerifOff()
    {
        $client        = self::createClient();
        $container     = $client->getContainer();
        $doctrine      = $container->get('doctrine');
        $entityManager = $doctrine->getManager();
        /**
 * @var EmailUserRepository $repository
*/
        $repository = $entityManager->getRepository(EmailUser::class);
        /**
 * @var Email $email
*/
        $email = $repository->findOneRandomToVerif(0);
        if (!($email instanceof Email)) {
            $this->markTestSkipped('test désactivé');
            return;
        }

        $router = $client->getContainer()->get('router');
        $url    = $router->generate(
            'app_confirm_mail',
            [
                'id' => $email->getId(),
            ]
        );
        $client->request(Request::METHOD_GET, $url);
        $response = $client->getResponse();
        $this->assertTrue($response->isRedirection());
    }

    public function testConfirmMailVerifOn()
    {
        $client        = self::createClient();
        $container     = $client->getContainer();
        $doctrine      = $container->get('doctrine');
        $entityManager = $doctrine->getManager();
        /**
 * @var EmailUserRepository $repository
*/
        $repository = $entityManager->getRepository(EmailUser::class);
        /**
 * @var Email $email
*/
        $email = $repository->findOneRandomToVerif(1);
        if (!($email instanceof Email)) {
            $this->markTestSkipped('test désactivé');
            return;
        }

        $router = $client->getContainer()->get('router');
        $url    = $router->generate(
            'app_confirm_mail',
            [
                'id' => $email->getId(),
            ]
        );
        $client->request(Request::METHOD_GET, $url);
        $response = $client->getResponse();
        $this->assertTrue($response->isRedirection());
    }
}
