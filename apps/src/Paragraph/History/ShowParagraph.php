<?php

namespace Labstag\Paragraph\History;

use Labstag\Entity\History;
use Labstag\Entity\Layout;
use Labstag\Entity\Paragraph\History\Show;
use Labstag\Form\Admin\Paragraph\History\ShowType;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\HistoryRepository;

class ShowParagraph extends ParagraphLib
{
    public function getEntity()
    {
        return Show::class;
    }

    public function getForm()
    {
        return ShowType::class;
    }

    public function getName()
    {
        return $this->translator->trans('historyshow.name', [], 'paragraph');
    }

    public function getType()
    {
        return 'historyshow';
    }

    public function isShowForm()
    {
        return false;
    }

    public function show(Show $historyshow)
    {
        $all        = $this->request->attributes->all();
        $routeParam = $all['_route_params'];
        $slug       = $routeParam['slug'] ?? null;
        /** @var HistoryRepository $repository */
        $repository = $this->getRepository(History::class);
        $history    = $repository->findOneBy(
            ['slug' => $slug]
        );

        if (!$history instanceof History) {
            return;
        }

        return $this->render(
            $this->getParagraphFile('history/show'),
            [
                'history'   => $history,
                'paragraph' => $historyshow,
            ]
        );
    }

    public function useIn()
    {
        return [
            Layout::class,
        ];
    }
}
