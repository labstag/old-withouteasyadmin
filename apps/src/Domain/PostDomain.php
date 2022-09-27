<?php

namespace Labstag\Domain;

use Labstag\Entity\Post;

use Labstag\Form\Admin\PostType;
use Labstag\Form\Admin\Search\PostType as SearchPostType;
use Labstag\Lib\DomainLib;
use Labstag\Repository\PostRepository;
use Labstag\RequestHandler\PostRequestHandler;
use Labstag\Search\PostSearch;
use Symfony\Contracts\Translation\TranslatorInterface;

class PostDomain extends DomainLib
{
    public function __construct(
        protected PostRequestHandler $postRequestHandler,
        protected PostRepository $postRepository,
        TranslatorInterface $translator
    )
    {
        parent::__construct($translator);
    }

    public function getEntity()
    {
        return Post::class;
    }

    public function getRepository()
    {
        return $this->postRepository;
    }

    public function getRequestHandler()
    {
        return $this->postRequestHandler;
    }

    public function getSearchData()
    {
        return PostSearch::class;
    }

    public function getSearchForm()
    {
        return SearchPostType::class;
    }

    /**
     * @return mixed[]
     */
    public function getTitles(): array
    {
        return [
            'admin_post_index'   => $this->translator->trans('post.title', [], 'admin.breadcrumb'),
            'admin_post_edit'    => $this->translator->trans('post.edit', [], 'admin.breadcrumb'),
            'admin_post_new'     => $this->translator->trans('post.new', [], 'admin.breadcrumb'),
            'admin_post_trash'   => $this->translator->trans('post.trash', [], 'admin.breadcrumb'),
            'admin_post_preview' => $this->translator->trans('post.preview', [], 'admin.breadcrumb'),
            'admin_post_show'    => $this->translator->trans('post.show', [], 'admin.breadcrumb'),
        ];
    }

    public function getType()
    {
        return PostType::class;
    }

    public function getUrlAdmin(): array
    {
        return [
            'delete'   => 'api_action_delete',
            'destroy'  => 'api_action_destroy',
            'edit'     => 'admin_post_edit',
            'empty'    => 'api_action_empty',
            'list'     => 'admin_post_index',
            'new'      => 'admin_post_new',
            'preview'  => 'admin_post_preview',
            'restore'  => 'api_action_restore',
            'show'     => 'admin_post_show',
            'trash'    => 'admin_post_trash',
            'workflow' => 'api_action_workflow',
        ];
    }
}
