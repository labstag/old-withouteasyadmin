<?php

namespace Labstag\Paragraph;

use Labstag\Entity\Edito as EntityEdito;
use Labstag\Entity\Page;
use Labstag\Entity\Paragraph\Edito;
use Labstag\Form\Admin\Paragraph\EditoType;
use Labstag\Interfaces\ParagraphInterface;
use Labstag\Lib\ParagraphLib;
use Labstag\Repository\EditoRepository;
use Symfony\Component\HttpFoundation\Response;

class EditoParagraph extends ParagraphLib
{
    public function getCode(ParagraphInterface $entityParagraphLib): string
    {
        unset($entityParagraphLib);

        return 'edito';
    }

    public function getEntity(): string
    {
        return Edito::class;
    }

    public function getForm(): string
    {
        return EditoType::class;
    }

    public function getName(): string
    {
        return $this->translator->trans('edito.name', [], 'paragraph');
    }

    public function getType(): string
    {
        return 'edito';
    }

    public function isShowForm(): bool
    {
        return false;
    }

    public function show(Edito $edito): Response
    {
        /** @var EditoRepository $serviceEntityRepositoryLib */
        $serviceEntityRepositoryLib = $this->repositoryService->get(EntityEdito::class);

        return $this->render(
            $this->getTemplateFile($this->getCode($edito)),
            [
                'edito'     => $serviceEntityRepositoryLib->findOnePublier(),
                'paragraph' => $edito,
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
