<?php

namespace Labstag\Paragraph\History;

use Labstag\Entity\History;
use Labstag\Entity\Page;
use Labstag\Entity\Paragraph\History\Liste;
use Labstag\Form\Admin\Paragraph\History\ListType;
use Labstag\Interfaces\ParagraphInterface;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\HistoryRepository;
use Symfony\Component\HttpFoundation\Response;

class ListParagraph extends ParagraphLib
{
    public function getCode(ParagraphInterface $entityParagraphLib): string
    {
        unset($entityParagraphLib);

        return 'history/list';
    }

    public function getEntity(): string
    {
        return Liste::class;
    }

    public function getForm(): string
    {
        return ListType::class;
    }

    public function getName(): string
    {
        return $this->translator->trans('historylist.name', [], 'paragraph');
    }

    public function getType(): string
    {
        return 'historylist';
    }

    public function isShowForm(): bool
    {
        return false;
    }

    public function show(Liste $liste): Response
    {
        /** @var HistoryRepository $entityRepository */
        $entityRepository = $this->getRepository(History::class);

        $pagination = $this->paginator->paginate(
            $entityRepository->findPublier(),
            $this->request->query->getInt('page', 1),
            10
        );

        return $this->render(
            $this->getTemplateFile($this->getCode($liste)),
            [
                'pagination' => $pagination,
                'paragraph'  => $liste,
            ]
        );
    }

    /**
     * @return array<class-string<Page>>
     */
    public function useIn(): array
    {
        return [
            Page::class,
        ];
    }
}
