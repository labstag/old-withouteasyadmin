<?php

namespace Labstag\Paragraph\History;

use Labstag\Entity\History;
use Labstag\Entity\Layout;
use Labstag\Entity\Paragraph\History\Show;
use Labstag\Form\Admin\Paragraph\History\ShowType;
use Labstag\Interfaces\ParagraphInterface;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\HistoryRepository;
use Symfony\Component\HttpFoundation\Response;

class ShowParagraph extends ParagraphLib
{
    public function getCode(ParagraphInterface $entityParagraphLib): string
    {
        unset($entityParagraphLib);

        return 'history/show';
    }

    public function getEntity(): string
    {
        return Show::class;
    }

    public function getForm(): string
    {
        return ShowType::class;
    }

    public function getName(): string
    {
        return $this->translator->trans('historyshow.name', [], 'paragraph');
    }

    public function getType(): string
    {
        return 'historyshow';
    }

    public function isShowForm(): bool
    {
        return false;
    }

    public function show(Show $show): ?Response
    {
        $all = $this->request->attributes->all();
        $routeParam = $all['_route_params'];
        $slug = $routeParam['slug'] ?? null;
        /** @var HistoryRepository $entityRepository */
        $entityRepository = $this->getRepository(History::class);
        $history = $entityRepository->findOneBy(
            ['slug' => $slug]
        );

        if (!$history instanceof History) {
            return null;
        }

        return $this->render(
            $this->getTemplateFile($this->getCode($show)),
            [
                'history'   => $history,
                'paragraph' => $show,
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
