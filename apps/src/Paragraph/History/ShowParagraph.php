<?php

namespace Labstag\Paragraph\History;

use Labstag\Entity\History;
use Labstag\Entity\Layout;
use Labstag\Entity\Paragraph\History\Show;
use Labstag\Form\Admin\Paragraph\History\ShowType;
use Labstag\Interfaces\EntityParagraphInterface;
use Labstag\Interfaces\ParagraphInterface;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\HistoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ShowParagraph extends ParagraphLib implements ParagraphInterface
{
    public function getCode(EntityParagraphInterface $entityParagraph): string
    {
        unset($entityParagraph);

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

    public function show(EntityParagraphInterface $entityParagraph): ?Response
    {
        /** @var Request $request */
        $request    = $this->requestStack->getCurrentRequest();
        $all        = $request->attributes->all();
        $routeParam = $all['_route_params'];
        $slug       = $routeParam['slug'] ?? null;
        /** @var HistoryRepository $serviceEntityRepositoryLib */
        $serviceEntityRepositoryLib = $this->repositoryService->get(History::class);
        $history                    = $serviceEntityRepositoryLib->findOneBy(
            ['slug' => $slug]
        );

        if (!$history instanceof History) {
            return null;
        }

        return $this->render(
            $this->getTemplateFile($this->getCode($entityParagraph)),
            [
                'history'   => $history,
                'paragraph' => $entityParagraph,
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
