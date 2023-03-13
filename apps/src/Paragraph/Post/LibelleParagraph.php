<?php

namespace Labstag\Paragraph\Post;

use Labstag\Entity\Layout;
use Labstag\Entity\Paragraph\Post\Libelle;
use Labstag\Entity\Post;
use Labstag\Form\Admin\Paragraph\Post\LibelleType;
use Labstag\Interfaces\EntityParagraphInterface;
use Labstag\Interfaces\ParagraphInterface;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LibelleParagraph extends ParagraphLib implements ParagraphInterface
{
    public function getCode(EntityParagraphInterface $entityParagraph): string
    {
        unset($entityParagraph);

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

    public function show(EntityParagraphInterface $entityParagraph): Response
    {
        /** @var Request $request */
        $request    = $this->requestStack->getCurrentRequest();
        $all        = $request->attributes->all();
        $routeParam = $all['_route_params'];
        $slug       = $routeParam['slug'] ?? null;
        /** @var PostRepository $serviceEntityRepositoryLib */
        $serviceEntityRepositoryLib = $this->repositoryService->get(Post::class);
        $pagination                 = $this->paginator->paginate(
            $serviceEntityRepositoryLib->findPublierLibelle($slug),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render(
            $this->getTemplateFile($this->getCode($entityParagraph)),
            [
                'pagination' => $pagination,
                'paragraph'  => $entityParagraph,
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
