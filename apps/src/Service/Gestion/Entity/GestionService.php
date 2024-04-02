<?php

namespace Labstag\Service\Gestion\Entity;

use Labstag\Entity\Memo;
use Labstag\Form\Gestion\FormType;
use Labstag\Interfaces\AdminEntityServiceInterface;
use Labstag\Repository\MemoRepository;
use Labstag\Service\Gestion\ViewService;
use Symfony\Component\HttpFoundation\Response;

class GestionService extends ViewService implements AdminEntityServiceInterface
{
    public function getType(): string
    {
        return 'gestion';
    }

    public function home(): Response
    {
        /** @var MemoRepository $memoRepository */
        $memoRepository = $this->entityManager->getRepository(Memo::class);
        $memos          = $memoRepository->findPublier();

        return $this->render(
            'gestion/index.html.twig',
            ['memos' => $memos]
        );
    }

    public function oauth(): Response
    {
        $types = $this->oauthService->getConfigProvider();

        return $this->render(
            'gestion/oauth.html.twig',
            ['types' => $types]
        );
    }

    public function themes(string $state): Response
    {
        $data = [
            'buttons'     => [[]],
            'choice'      => [[]],
            'dateandtime' => [[]],
            'hidden'      => [[]],
            'extra'       => [[]],
            'other'       => [[]],
            'text'        => [[]],
            'collection'  => [[]],
        ];
        $form = $this->createForm(FormType::class, $data);

        return $this->render(
            $state.'/form.html.twig',
            ['form' => $form]
        );
    }
}
