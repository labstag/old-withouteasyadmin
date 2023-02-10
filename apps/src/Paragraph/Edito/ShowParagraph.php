<?php

namespace Labstag\Paragraph\Edito;

use Labstag\Entity\Edito;
use Labstag\Entity\Layout;
use Labstag\Entity\Paragraph\Edito\Show;
use Labstag\Form\Admin\Paragraph\Edito\ShowType;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\EditoRepository;

class ShowParagraph extends ParagraphLib
{
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
        return $this->translator->trans('editoshow.name', [], 'paragraph');
    }

    public function getType(): string
    {
        return 'editoshow';
    }

    public function isShowForm(): bool
    {
        return false;
    }

    public function show(Show $show)
    {
        /** @var EditoRepository $entityRepository */
        $entityRepository = $this->getRepository(Edito::class);
        $edito = $entityRepository->findOnePublier();

        if (!$edito instanceof Edito) {
            return;
        }

        return $this->render(
            $this->getParagraphFile('edito/show'),
            [
                'edito'     => $edito,
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
