<?php

namespace Labstag\Tests;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

trait IntegrationTrait
{

    protected function responseTest(
        $route,
        string $groupe,
        bool $bool,
        array $params = []
    )
    {
        $client = $this->logIn($groupe);
        $router = $client->getContainer()->get('router');
        $url    = $router->generate($route, $params);
        $client->request(Request::METHOD_GET, $url);
        $response = $client->getResponse();
        $this->assertTrue($response->isSuccessful() == $bool);
    }

    protected function showEditDataNotFound(string $groupe, $route)
    {
        $client = $this->logIn($groupe);
        $router = $client->getContainer()->get('router');
        $url    = $router->generate(
            $route,
            ['id' => 'null']
        );
        $client->request(Request::METHOD_GET, $url);
        $response = $client->getResponse();
        $this->assertFalse($response->isSuccessful());
    }

    public function addNewEntity(
        string $groupe,
        bool $bool,
        string $route,
        string $formClass
    )
    {
        if (!$bool) {
            $this->markTestSkipped('test désactivé pour le groupe ' . $groupe);
            return;
        }

        $client   = $this->logIn($groupe);
        $entity   = $this->getNewEntity($client);
        $newform  = $this->createForm($client, $formClass, $entity);
        $router   = $client->getContainer()->get('router');
        $url      = $router->generate($route);
        $crawler  = $client->request(Request::METHOD_GET, $url);
        $nameForm = $newform->getName();
        $filter   = $crawler->filter('form[name="' . $nameForm . '"]');
        $form     = $filter->form();
        $values   = $form->getPhpValues();
        $methods  = get_class_methods($entity);
        $post     = [];
        foreach (array_keys($values[$nameForm]) as $key) {
            if (in_array('get' . ucfirst($key), $methods)) {
                $method = 'get' . ucfirst($key);
                $value  = $entity->$method();
                $index  = $nameForm . '[' . $key . ']';
                if (is_object($value)) {
                    $post[$index] = $value->getId();
                    continue;
                } elseif (isset($values[$nameForm][$key]['first'])) {
                    $post[$index . '[first]']  = $value;
                    $post[$index . '[second]'] = $value;
                    unset($values[$nameForm][$key]);
                    continue;
                }

                $post[$index] = $value;
            }
        }

        foreach ($values[$nameForm] as $key => $value) {
            if ($key == '_token') {
                continue;
            }

            $index = $nameForm . '[' . $key . ']';
            if (!isset($post[$index])) {
                $post[$index] = $value;
            }
        }

        $form = $filter->form($post);
        $client->submit($form);
        $response = $client->getResponse();
        $this->assertFalse($response->isSuccessful());
    }

    /**
     * Creates and returns a Form instance from the type of the form.
     */
    protected function createForm(
        $client,
        string $type,
        $data = null,
        array $options = []
    )
    {
        return $client->getContainer()->get('form.factory')->create(
            $type,
            $data,
            $options
        );
    }

    public function editPostRedirect(
        string $groupe,
        string $route,
        $formClass,
        bool $bool,
        string $method = 'getEntity'
    )
    {
        $this->editPost(
            $groupe,
            $route,
            $formClass,
            $bool,
            true,
            $method
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_FOUND);
    }

    public function editPostNoRedirect(
        string $groupe,
        string $route,
        $formClass,
        bool $bool,
        string $method = 'getEntity'
    )
    {
        $this->editPost(
            $groupe,
            $route,
            $formClass,
            $bool,
            false,
            $method,
        );
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    private function editPost(
        string $groupe,
        string $route,
        $formClass,
        bool $bool,
        bool $redirect,
        string $method = 'getEntity'
    )
    {
        if (!$bool) {
            $this->markTestSkipped('test désactivé pour le groupe ' . $groupe);
            return;
        }

        $client = $this->logIn($groupe);
        $entity = $this->$method($client);
        if (is_null($entity)) {
            $this->markTestSkipped('data introuvable');
            return;
        }

        $newform = $this->createForm($client, $formClass);

        $router = $client->getContainer()->get('router');
        $params = [];
        if (is_object($entity)) {
            $params['id'] = $entity->getId();
        }

        $url     = $router->generate($route, $params);
        $crawler = $client->request(Request::METHOD_GET, $url);

        $filter = $crawler->filter('form[name="' . $newform->getName() . '"]');
        $form   = $filter->form();
        $client->submit($form);
        $response = $client->getResponse();
        if ($redirect) {
            $this->assertFalse($response->isSuccessful());
            return;
        }

        $this->assertTrue($response->isSuccessful());
    }

    public function editDelete(string $groupe, bool $bool, $route)
    {
        if (!$bool) {
            $this->markTestSkipped('test désactivé pour le groupe ' . $groupe);
            return;
        }

        $client = $this->logIn($groupe);
        $data   = $this->getEntity($client);
        if (is_null($data)) {
            $this->markTestSkipped('data introuvable');
            return;
        }

        $router = $client->getContainer()->get('router');
        $url    = $router->generate(
            $route,
            [
                'id' => $data->getId(),
            ]
        );
        $client->request(Request::METHOD_POST, $url);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    private function showTest(string $groupe, bool $bool, string $route)
    {
        $client = $this->logIn($groupe);
        $data   = $this->getEntity($client);
        if (is_null($data)) {
            $this->markTestSkipped('data introuvable');
            return;
        }

        $this->responseTest(
            $route,
            $groupe,
            $bool,
            ['id' => $data->getId()]
        );
    }
}
