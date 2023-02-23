<?php

namespace Labstag\Paragraph\Post;

use Labstag\Entity\Layout;
use Labstag\Entity\Paragraph\Post\Libelle;
use Labstag\Entity\Post;
use Labstag\Form\Admin\Paragraph\Post\LibelleType;
use Labstag\Lib\EntityParagraphLib;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Response;

class LibelleParagraph extends ParagraphLib
{
    public function getCode(EntityParagraphLib $entityParagraphLib): string
    {
        unset($entityParagraphLib);

        return 'post/libelle';
    }

    public function getEntity(): string
    {
        return Libelle::class;
    }

    public function getForm(): string
    {
        return LibelleType::class;
    }

    public function getName(): string
    {
        return $this->translator->trans('postlibelle.name', [], 'paragraph');
    }

    public function getType(): string
    {
        return 'postlibelle';
    }

    public function isShowForm(): bool
    {
        return false;
    }

    public function show(Libelle $libelle): Response
    {
        $all = $this->request->attributes->all();
        $routeParam = $all['_route_params'];
        $slug = $routeParam['slug'] ?? null;
        /** @var PostRepository $entityRepository */
        $entityRepository = $this->getRepository(Post::class);
        $pagination = $this->paginator->paginate(
            $entityRepository->findPublierLibelle($slug),
            $this->request->query->getInt('page', 1),
            10
        );

        return $this->render(
            $this->getTemplateFile($this->getCode($libelle)),
            [
                'pagination' => $pagination,
                'paragraph'  => $libelle,
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
