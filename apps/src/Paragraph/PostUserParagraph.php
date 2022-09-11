<?php

namespace Labstag\Paragraph;

use Labstag\Entity\Layout;
use Labstag\Entity\Paragraph\PostUser;
use Labstag\Entity\Post as EntityPost;
use Labstag\Form\Admin\Paragraph\PostUserType;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\Paragraph\PostUserRepository;

class PostUserParagraph extends ParagraphLib
{
    public function getEntity()
    {
        return PostUser::class;
    }

    public function getForm()
    {
        return PostUserType::class;
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

    public function show(PostUser $postuser)
    {
        $all        = $this->request->attributes->all();
        $routeParam = $all['_route_params'];
        $username   = $routeParam['username'] ?? null;
        /** @var PostUserRepository $repository */
        $repository = $this->getRepository(EntityPost::class);
        $pagination = $this->paginator->paginate(
            $repository->findPublierUsername($username),
            $this->request->query->getInt('page', 1),
            10
        );

        return $this->render(
            $this->getParagraphFile('postuser'),
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
