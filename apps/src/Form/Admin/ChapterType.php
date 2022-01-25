<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\Chapter;
use Labstag\FormType\WysiwygType;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChapterType extends AbstractTypeLib
{
    /**
     * @inheritdoc
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        $this->setTextType($builder);
        $builder->add(
            'published',
            DateTimeType::class,
            [
                'label'        => $this->translator->trans('chapter.published.label', [], 'admin.form'),
                'help'         => $this->translator->trans('chapter.published.help', [], 'admin.form'),
                'date_widget'  => 'single_text',
                'time_widget'  => 'single_text',
                'with_seconds' => true,
            ]
        );
        $builder->add(
            'content',
            WysiwygType::class,
            [
                'label' => $this->translator->trans('chapter.content.label', [], 'admin.form'),
                'help'  => $this->translator->trans('chapter.content.help', [], 'admin.form'),
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

    protected function setMeta($builder)
    {
        $meta = [
            'metaDescription' => [
                'label' => $this->translator->trans('chapter.metaDescription.label', [], 'admin.form'),
                'help'  => $this->translator->trans('chapter.metaDescription.help', [], 'admin.form'),
            ],
            'metaKeywords'    => [
                'label' => $this->translator->trans('chapter.metaKeywords.label', [], 'admin.form'),
                'help'  => $this->translator->trans('chapter.metaKeywords.help', [], 'admin.form'),
            ],
        ];
        $this->setMetas($builder, $meta);
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
