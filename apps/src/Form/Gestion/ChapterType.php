<?php

namespace Labstag\Form\Gestion;

use Labstag\Entity\Chapter;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChapterType extends AbstractTypeLib
{
    /**
     * @inheritDoc
     */
    public function buildForm(
        FormBuilderInterface $formBuilder,
        array $options
    ): void
    {
        $this->setTextType($formBuilder);
        $this->addPublished($formBuilder);
        $this->setContent($formBuilder);
        $this->addParagraph(
            $formBuilder,
            [
                'add'    => 'gestion_chapter_paragraph_add',
                'edit'   => 'gestion_chapter_paragraph_show',
                'delete' => 'gestion_chapter_paragraph_delete',
            ]
        );
        $this->setMeta($formBuilder);
        unset($options);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => Chapter::class,
            ]
        );
    }

    protected function setTextType(FormBuilderInterface $formBuilder): void
    {
        $texttype = [
            'name' => [
                'label' => $this->translator->trans('chapter.name.label', [], 'gestion.form'),
                'help'  => $this->translator->trans('chapter.name.help', [], 'gestion.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('chapter.name.placeholder', [], 'gestion.form'),
                ],
            ],
            'slug' => [
                'label'    => $this->translator->trans('chapter.slug.label', [], 'gestion.form'),
                'help'     => $this->translator->trans('chapter.slug.help', [], 'gestion.form'),
                'required' => false,
                'attr'     => [
                    'placeholder' => $this->translator->trans('chapter.slug.placeholder', [], 'gestion.form'),
                ],
            ],
        ];
        foreach ($texttype as $key => $args) {
            $formBuilder->add($key, TextType::class, $args);
        }
    }
}
