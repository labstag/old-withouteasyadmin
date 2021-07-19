<?php

namespace Labstag\Controller;

use Labstag\Entity\Post;
use Labstag\Lib\FrontControllerLib;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/post")
 */
class PostController extends FrontControllerLib
{
    /**
     * @Route("/archive/{code}", name="post_archive")
     */
    public function archive($code)
    {
        echo $code;
        exit();
    }

    /**
     * @Route("/{slug}", name="post_show")
     */
    public function show(string $slug)
    {
        echo $slug;
        exit();
    }

    /**
     * @Route("/user/{username}", name="post_user")
     */
    public function user($username)
    {
        echo $username;
        exit();
    }
}
