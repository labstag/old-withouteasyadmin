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
     */
    public function category(Request $request): Response
    {
        return $this->showData($request, Category::class, 'findName');
    }

    /**
     * @Route("/group", name="api_search_group")
     */
    public function groupe(Request $request): Response
    {
        return $this->showData($request, Groupe::class, 'findName');
    }

    /**
     * @Route("/libelle", name="api_search_postlibelle")
     */
    public function libelle(Request $request): JsonResponse
    {
        return $this->showData($request, Libelle::class, 'findName');
    }

    /**
     * @Route("/user", name="api_search_user")
     */
    public function user(Request $request): Response
    {
        return $this->showData($request, User::class, 'findUserName');
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
