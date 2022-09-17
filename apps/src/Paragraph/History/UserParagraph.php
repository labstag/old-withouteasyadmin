<?php

namespace Labstag\Paragraph\History;

use Labstag\Entity\History;
use Labstag\Entity\Layout;
use Labstag\Entity\Paragraph\History\User;
use Labstag\Form\Admin\Paragraph\History\UserType;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\HistoryRepository;

class UserParagraph extends ParagraphLib
{
    public function getEntity()
    {
        return User::class;
    }

    public function getForm()
    {
        return UserType::class;
    }

    public function getName()
    {
        return $this->translator->trans('historyuser.name', [], 'paragraph');
    }

    public function getType()
    {
        return 'historyuser';
    }

    public function isShowForm()
    {
        return false;
    }

    public function show(User $user)
    {
        $all        = $this->request->attributes->all();
        $routeParam = $all['_route_params'];
        $username   = $routeParam['username'] ?? null;
        /** @var HistoryRepository $repository */
        $repository = $this->getRepository(History::class);
        $pagination = $this->paginator->paginate(
            $repository->findPublierUsername($username),
            $this->request->query->getInt('page', 1),
            10
        );

        return $this->render(
            $this->getParagraphFile('history/user'),
            [
                'pagination' => $pagination,
                'paragraph'  => $user,
            ]
        );
    }

    public function useIn()
    {
        return [
            Layout::class,
        ];
    }
}
