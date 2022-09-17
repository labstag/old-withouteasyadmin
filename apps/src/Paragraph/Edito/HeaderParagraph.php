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
    public function getEntity(): string
    {
        return Header::class;
    }

    public function getForm(): string
    {
        return HeaderType::class;
    }

    public function getName(): string
    {
        return $this->translator->trans('editoheader.name', [], 'paragraph');
    }

    public function getType(): string
    {
        return 'editoheader';
    }

    public function isHeaderForm(): bool
    {
        return false;
    }

    public function show(Header $header)
    {
        /** @var EditoRepository $entityRepository */
        $entityRepository = $this->getRepository(Edito::class);
        $edito      = $entityRepository->findOnePublier();

        if (!$edito instanceof Edito) {
            return;
        }

        return $this->render(
            $this->getParagraphFile('edito/header'),
            [
                'edito'     => $edito,
                'paragraph' => $header,
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
