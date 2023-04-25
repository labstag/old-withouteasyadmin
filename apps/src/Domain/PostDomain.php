<?php

namespace Labstag\Domain;

use Labstag\Entity\Post;

use Labstag\Form\Admin\PostType;
use Labstag\Form\Admin\Search\PostType as SearchPostType;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\DomainLib;
use Labstag\Search\PostSearch;

class PostDomain extends DomainLib implements DomainInterface
{
    public function getEntity(): string
    {
        return Post::class;
    }

    public function getSearchData(): PostSearch
    {
        return new PostSearch();
    }

    public function getSearchForm(): string
    {
        return SearchPostType::class;
    }

    public function getTemplates(): array
    {
        return [
            'index'   => 'admin/post/index.html.twig',
            'trash'   => 'admin/post/index.html.twig',
            'show'    => 'admin/post/show.html.twig',
            'preview' => 'admin/post/show.html.twig',
            'edit'    => 'admin/post/form.html.twig',
        ];
    }

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

    public function getType(): string
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
