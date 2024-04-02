<?php

namespace Labstag\Domain;

use Labstag\Entity\Post;

use Labstag\Form\Gestion\PostType;
use Labstag\Form\Gestion\Search\PostType as SearchPostType;
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
            'index'   => 'gestion/post/index.html.twig',
            'trash'   => 'gestion/post/index.html.twig',
            'show'    => 'gestion/post/show.html.twig',
            'preview' => 'gestion/post/show.html.twig',
            'edit'    => 'gestion/post/form.html.twig',
        ];
    }

    public function getTitles(): array
    {
        return [
            'gestion_post_index'   => $this->translator->trans('post.title', [], 'gestion.breadcrumb'),
            'gestion_post_edit'    => $this->translator->trans('post.edit', [], 'gestion.breadcrumb'),
            'gestion_post_new'     => $this->translator->trans('post.new', [], 'gestion.breadcrumb'),
            'gestion_post_trash'   => $this->translator->trans('post.trash', [], 'gestion.breadcrumb'),
            'gestion_post_preview' => $this->translator->trans('post.preview', [], 'gestion.breadcrumb'),
            'gestion_post_show'    => $this->translator->trans('post.show', [], 'gestion.breadcrumb'),
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
            'edit'     => 'gestion_post_edit',
            'empty'    => 'api_action_empty',
            'list'     => 'gestion_post_index',
            'new'      => 'gestion_post_new',
            'preview'  => 'gestion_post_preview',
            'restore'  => 'api_action_restore',
            'show'     => 'gestion_post_show',
            'trash'    => 'gestion_post_trash',
            'workflow' => 'api_action_workflow',
        ];
    }
}
