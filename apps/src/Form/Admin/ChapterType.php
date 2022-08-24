<?php

namespace Labstag\Form\Admin;

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
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        $this->setTextType($builder);
        $this->addPublished($builder);
        $this->setContent($builder);
        $this->addParagraph(
            $builder,
            [
                'add'    => 'admin_chapter_paragraph_add',
                'edit'   => 'admin_chapter_paragraph_show',
                'delete' => 'admin_chapter_paragraph_delete',
            ]
        );
        $this->setMeta($builder);
        unset($options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Chapter::class,
            ]
        );
    }

    protected function setTextType($builder)
    {
        $texttype = [
            'name' => [
                'label' => $this->translator->trans('chapter.name.label', [], 'admin.form'),
                'help'  => $this->translator->trans('chapter.name.help', [], 'admin.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('chapter.name.placeholder', [], 'admin.form'),
                ],
            ],
            'slug' => [
                'label'    => $this->translator->trans('chapter.slug.label', [], 'admin.form'),
                'help'     => $this->translator->trans('chapter.slug.help', [], 'admin.form'),
                'required' => false,
                'attr'     => [
                    'placeholder' => $this->translator->trans('chapter.slug.placeholder', [], 'admin.form'),
                ],
            ],
        ];
        foreach ($texttype as $key => $args) {
            $builder->add($key, TextType::class, $args);
        }
    }
}
