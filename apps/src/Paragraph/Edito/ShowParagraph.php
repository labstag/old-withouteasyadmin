<?php

namespace Labstag\Paragraph\Edito;

use Labstag\Entity\Layout;
use Labstag\Entity\Paragraph\Edito\Show;
use Labstag\Entity\Edito;
use Labstag\Form\Admin\Paragraph\Edito\ShowType;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\EditoRepository;

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
        return $this->translator->trans('editoshow.name', [], 'paragraph');
    }

    public function getType()
    {
        return 'editoshow';
    }

    public function isShowForm()
    {
        return false;
    }

    public function show(Show $editoshow)
    {
        /** @var EditoRepository $repository */
        $repository = $this->getRepository(Edito::class);
        $edito = $repository->findOnePublier();

        if (!$edito instanceof Edito) {
            return;
        }

        return $this->render(
            $this->getParagraphFile('edito/show'),
            [
                'edito'     => $edito,
                'paragraph' => $edito,
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
