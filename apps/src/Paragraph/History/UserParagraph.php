<?php

namespace Labstag\Paragraph\History;

use Labstag\Entity\History;
use Labstag\Entity\Layout;
use Labstag\Entity\Paragraph\History\User;
use Labstag\Form\Admin\Paragraph\History\UserType;
use Labstag\Interfaces\ParagraphInterface;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\HistoryRepository;
use Symfony\Component\HttpFoundation\Response;

class UserParagraph extends ParagraphLib
{
    public function getCode(ParagraphInterface $entityParagraphLib): string
    {
        unset($entityParagraphLib);

        return 'history/user';
    }

    public function getEntity(): string
    {
        return User::class;
    }

    public function getForm(): string
    {
        return UserType::class;
    }

    public function getName(): string
    {
        return $this->translator->trans('historyuser.name', [], 'paragraph');
    }

    public function getType(): string
    {
        return 'historyuser';
    }

    public function isShowForm(): bool
    {
        return false;
    }

    public function show(User $user): Response
    {
        $all = $this->request->attributes->all();
        $routeParam = $all['_route_params'];
        $username = $routeParam['username'] ?? null;
        /** @var HistoryRepository $entityRepository */
        $entityRepository = $this->getRepository(History::class);
        $pagination = $this->paginator->paginate(
            $entityRepository->findPublierUsername($username),
            $this->request->query->getInt('page', 1),
            10
        );

        return $this->render(
            $this->getTemplateFile($this->getCode($user)),
            [
                'pagination' => $pagination,
                'paragraph'  => $user,
            ]
        );
    }

    /**
     * @return array<class-string<Layout>>
     */
    public function useIn(): array
    {
        return [
            Layout::class,
        ];
    }
}
