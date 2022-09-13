<?php

namespace Labstag\Paragraph\Edito;

use Labstag\Entity\Edito;
use Labstag\Entity\Layout;
use Labstag\Entity\Paragraph\Edito\Header;
use Labstag\Form\Admin\Paragraph\Edito\HeaderType;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\EditoRepository;

class HeaderParagraph extends ParagraphLib
{
    public function getEntity()
    {
        return Header::class;
    }

    public function getForm()
    {
        return HeaderType::class;
    }

    public function getName()
    {
        return $this->translator->trans('editoheader.name', [], 'paragraph');
    }

    public function getType()
    {
        return 'editoheader';
    }

    public function isHeaderForm()
    {
        return false;
    }

    public function show(Header $editoheader)
    {
        /** @var EditoRepository $repository */
        $repository = $this->getRepository(Edito::class);
        $edito      = $repository->findOnePublier();

        if (!$edito instanceof Edito) {
            return;
        }

        return $this->render(
            $this->getParagraphFile('edito/header'),
            [
                'edito'     => $edito,
                'paragraph' => $editoheader,
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
