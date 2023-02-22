<?php

namespace Labstag\Controller\Api;

use Labstag\Entity\Category;
use Labstag\Entity\Groupe;
use Labstag\Entity\Libelle;
use Labstag\Entity\User;
use Labstag\Lib\ApiControllerLib;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/search')]
class SearchController extends ApiControllerLib
{
    #[Route(path: '/category', name: 'api_search_category')]
    #[Route(path: '/group', name: 'api_search_group')]
    #[Route(path: '/libelle', name: 'api_search_postlibelle')]
    #[Route(path: '/user', name: 'api_search_user')]
    public function libelle(Request $request): JsonResponse
    {
        $attributes = $request->attributes->all();
        $route = $attributes['_route'];
        $entityName = match ($route) {
            'api_search_user' => User::class,
            'api_search_category' => Category::class,
            'api_search_group' => Groupe::class,
            'api_search_postlibelle' => Libelle::class,
            default => null
        };

        $function = match ($route) {
            'api_search_user' => 'findUserName',
            default => 'fidName'
        };

        return $this->showData($request, $entityName, $function);
    }

    private function showData($request, $entity, $method): JsonResponse
    {
        $get = $request->query->all();
        $return = ['isvalid' => false];
        if (!array_key_exists('name', $get) || is_null($entity)) {
            return $this->json($return);
        }

        $data = call_user_func([$this->entityManager->getRepository($entity), $method], $get['name']);
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
