<?php

namespace Labstag\Paragraph;

use Labstag\Entity\Page;
use Labstag\Entity\Paragraph\Form;
use Labstag\Form\Admin\Paragraph\FormType;
use Labstag\Interfaces\EntityParagraphInterface;
use Labstag\Interfaces\ParagraphInterface;
use Labstag\Lib\ParagraphLib;
use Symfony\Component\HttpFoundation\Response;

class FormParagraph extends ParagraphLib implements ParagraphInterface
{
    public function getCode(EntityParagraphInterface $entityParagraph): string
    {
        unset($entityParagraph);

        return 'form';
    }

    public function getEntity(): string
    {
        return Form::class;
    }

    public function getForm(): string
    {
        return FormType::class;
    }

    public function getName(): string
    {
        return $this->translator->trans('form.name', [], 'paragraph');
    }

    public function getType(): string
    {
        return 'form';
    }

    public function isShowForm(): bool
    {
        return true;
    }

    public function show(EntityParagraphInterface $entityParagraph): Response
    {
        return $this->render(
            $this->getTemplateFile($this->getCode($entityParagraph)),
            ['paragraph' => $entityParagraph]
        );
    }

    public function useIn(): array
    {
        return [
            Page::class,
        ];
    }
}
