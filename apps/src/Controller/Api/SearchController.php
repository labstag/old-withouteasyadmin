<?php

namespace Labstag\Controller\Api;

use Labstag\Entity\Category;
use Labstag\Entity\Groupe;
use Labstag\Entity\Libelle;
use Labstag\Entity\User;
use Labstag\Lib\ApiControllerLib;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/search")
 */
class SearchController extends ApiControllerLib
{
    /**
     * @Route("/category", name="api_search_category")
     * @Route("/group", name="api_search_group")
     * @Route("/libelle", name="api_search_postlibelle")
     * @Route("/user", name="api_search_user")
     */
    public function libelle(Request $request): JsonResponse
    {
        $attributes = $request->attributes->all();
        $route      = $attributes['_route'];
        $entityName = ($route == 'api_search_category') ? Category::class : null;
        $entityName = ($route == 'api_search_group') ? Groupe::class : null;
        $entityName = ($route == 'api_search_postlibelle') ? Libelle::class : null;

        $function = ($route == 'api_search_user') ? 'findUserName' : 'findName';
        return $this->showData($request, $entityName, $function);
    }

    private function showData($request, $entity, $method)
    {
        $get    = $request->query->all();
        $return = ['isvalid' => false];
        if (!array_key_exists('name', $get)) {
            return $this->json($return);
        }

        $data   = $this->getRepository($entity)->{$method}($get['name']);
        $result = [
            'results' => [],
        ];

        foreach ($data as $user) {
            $result['results'][] = [
                'id'   => $user->getId(),
                'text' => (string) $user,
            ];
        }

        return $this->json($result);
    }
}
