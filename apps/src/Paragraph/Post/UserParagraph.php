<?php

namespace Labstag\Paragraph\Post;

use Labstag\Entity\Layout;
use Labstag\Entity\Paragraph\Post\User;
use Labstag\Entity\Post;
use Labstag\Form\Admin\Paragraph\Post\UserType;
use Labstag\Interfaces\ParagraphInterface;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Response;

class UserParagraph extends ParagraphLib
{
    public function getCode(ParagraphInterface $entityParagraphLib): string
    {
        unset($entityParagraphLib);

        return 'post/user';
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
        return $this->translator->trans('postuser.name', [], 'paragraph');
    }

    public function getType(): string
    {
        return 'postuser';
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
        /** @var PostRepository $serviceEntityRepositoryLib */
        $serviceEntityRepositoryLib = $this->repositoryService->get(Post::class);
        $pagination = $this->paginator->paginate(
            $serviceEntityRepositoryLib->findPublierUsername($username),
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
