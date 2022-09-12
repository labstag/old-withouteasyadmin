<?php

namespace Labstag\Paragraph;

use Labstag\Entity\Edito as EntityEdito;
use Labstag\Entity\Page;
use Labstag\Entity\Paragraph\Edito;
use Labstag\Form\Admin\Paragraph\EditoType;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\EditoRepository;

class EditoParagraph extends ParagraphLib
{
    public function getEntity()
    {
        return Edito::class;
    }

    public function getForm()
    {
        return EditoType::class;
    }

    public function getName()
    {
        return $this->translator->trans('edito.name', [], 'paragraph');
    }

    public function getType()
    {
        return 'edito';
    }

    public function isShowForm()
    {
        return false;
    }

    public function show(Edito $edito)
    {
        /** @var EditoRepository $repository */
        $repository = $this->getRepository(EntityEdito::class);

        return $this->render(
            $this->getParagraphFile('edito'),
            [
                'edito'     => $repository->findOnePublier(),
                'paragraph' => $edito,
            ]
        );
    }

    public function useIn()
    {
        return [
            Page::class,
        ];
    }
}
