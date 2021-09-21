<?php

namespace Labstag\Controller\Api;

use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\CategoryRepository;
use Labstag\Repository\GroupeRepository;
use Labstag\Repository\LibelleRepository;
use Labstag\Repository\UserRepository;
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
    public function category(Request $request, CategoryRepository $repository): Response
    {
        $get    = $request->query->all();
        $return = ['isvalid' => false];
        if (!array_key_exists('name', $get)) {
            return $this->json($return);
        }

        $data   = $repository->findName($get['name']);
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

    /**
     * @Route("/group", name="api_search_group")
     */
    public function groupe(Request $request, GroupeRepository $repository): Response
    {
        $get    = $request->query->all();
        $return = ['isvalid' => false];
        if (!array_key_exists('name', $get)) {
            return $this->json($return);
        }

        $data   = $repository->findName($get['name']);
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

    /**
     * @Route("/libelle", name="api_search_postlibelle")
     *
     * @return void
     */
    public function libelle(Request $request, LibelleRepository $repository): Response
    {
        $get    = $request->query->all();
        $return = ['isvalid' => false];
        if (!array_key_exists('name', $get)) {
            return $this->json($return);
        }

        $data   = $repository->findNom($get['name']);
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

    /**
     * @Route("/user", name="api_search_user")
     */
    public function user(Request $request, UserRepository $repository): Response
    {
        $get    = $request->query->all();
        $return = ['isvalid' => false];
        if (!array_key_exists('name', $get)) {
            return $this->json($return);
        }

        $data   = $repository->findUserName($get['name']);
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
