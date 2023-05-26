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
    public function getCode(EntityParagraphInterface $entityParagraph): array
    {
        /** @var FormParagraph $entityParagraph */
        $code = $entityParagraph->getForm();

        return [
            'form/'.$code,
            'form/default',
        ];
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

    public function show(EntityParagraphInterface $entityParagraph): ?Response
    {
        $template = $this->getTemplateFile($this->getCode($entityParagraph));
        /** @var FormParagraph $entityParagraph */
        $code      = $entityParagraph->getForm();
        $formClass = $this->formService->init($code);

        return $this->formService->execute(
            $formClass,
            $template,
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
