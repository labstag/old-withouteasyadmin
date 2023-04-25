<?php

namespace Labstag\Service\Admin\Entity;

use DateTime;
use Exception;
use Labstag\Entity\Post;
use Labstag\Interfaces\AdminEntityServiceInterface;
use Labstag\Repository\PostRepository;
use Labstag\Service\Admin\ViewService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Uid\Uuid;

class PostService extends ViewService implements AdminEntityServiceInterface
{
    public function add(
        Security $security
    ): RedirectResponse {
        $routes = $this->getDomain()->getUrlAdmin();
        if (!isset($routes['edit']) || !isset($routes['list'])) {
            throw new Exception('Route edit not found');
        }

        $user = $security->getUser();
        if (is_null($user)) {
            return $this->redirectToRoute($routes['list']);
        }

        $post = new Post();
        $post->setPublished(new DateTime());
        $post->setRemark(false);
        $post->setTitle(Uuid::v1());
        $post->setRefuser($user);

        /** @var PostRepository $repositoryLib */
        $repositoryLib = $this->repositoryService->get(Post::class);
        $repositoryLib->save($post);

        return $this->redirectToRoute($routes['edit'], ['id' => $post->getId()]);
    }

    public function getType(): string
    {
        return Post::class;
    }
}
