<?php

namespace Labstag\Paragraph\Post;

use Labstag\Entity\Layout;
use Labstag\Entity\Paragraph\Post\User;
use Labstag\Entity\Post;
use Labstag\Form\Admin\Paragraph\Post\UserType;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\PostRepository;

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
        return $this->translator->trans('postuser.name', [], 'paragraph');
    }

    public function getType()
    {
        return 'postuser';
    }

    public function isShowForm()
    {
        return false;
    }

    public function show(User $postuser)
    {
        $all        = $this->request->attributes->all();
        $routeParam = $all['_route_params'];
        $username   = $routeParam['username'] ?? null;
        /** @var PostRepository $repository */
        $repository = $this->getRepository(Post::class);
        $pagination = $this->paginator->paginate(
            $repository->findPublierUsername($username),
            $this->request->query->getInt('page', 1),
            10
        );

        return $this->render(
            $this->getParagraphFile('post/user'),
            [
                'pagination' => $pagination,
                'paragraph'  => $postuser,
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
