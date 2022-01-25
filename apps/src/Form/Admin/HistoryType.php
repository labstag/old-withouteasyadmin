<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\History;
use Labstag\Entity\User;
use Labstag\FormType\SearchableType;
use Labstag\FormType\WysiwygType;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HistoryType extends AbstractTypeLib
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
                'label'        => $this->translator->trans('history.published.label', [], 'admin.form'),
                'help'         => $this->translator->trans('history.published.help', [], 'admin.form'),
                'date_widget'  => 'single_text',
                'time_widget'  => 'single_text',
                'with_seconds' => true,
            ]
        );
        $builder->add(
            'summary',
            WysiwygType::class,
            [
                'label' => $this->translator->trans('history.summary.label', [], 'admin.form'),
                'help'  => $this->translator->trans('history.summary.help', [], 'admin.form'),
            ]
        );
        $this->setMeta($builder);
        $builder->add(
            'refuser',
            SearchableType::class,
            [
                'label'    => $this->translator->trans('history.refuser.label', [], 'admin.form'),
                'help'     => $this->translator->trans('history.refuser.help', [], 'admin.form'),
                'multiple' => false,
                'class'    => User::class,
                'route'    => 'api_search_user',
                'attr'     => [
                    'placeholder' => $this->translator->trans('history.refuser.placeholder', [], 'admin.form'),
                ],
            ]
        );
        unset($options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => History::class,
            ]
        );
    }

    protected function setMeta($builder)
    {
        $meta = [
            'metaDescription' => [
                'label' => $this->translator->trans('history.metaDescription.label', [], 'admin.form'),
                'help'  => $this->translator->trans('history.metaDescription.help', [], 'admin.form'),
            ],
            'metaKeywords'    => [
                'label' => $this->translator->trans('history.metaKeywords.label', [], 'admin.form'),
                'help'  => $this->translator->trans('history.metaKeywords.help', [], 'admin.form'),
            ],
        ];
        $this->setMetas($builder, $meta);
    }

    protected function setTextType($builder)
    {
        $texttype = [
            'name' => [
                'label' => $this->translator->trans('history.name.label', [], 'admin.form'),
                'help'  => $this->translator->trans('history.name.help', [], 'admin.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('history.name.placeholder', [], 'admin.form'),
                ],
            ],
            'slug' => [
                'label'    => $this->translator->trans('history.slug.label', [], 'admin.form'),
                'help'     => $this->translator->trans('history.slug.help', [], 'admin.form'),
                'required' => false,
                'attr'     => [
                    'placeholder' => $this->translator->trans('history.slug.placeholder', [], 'admin.form'),
                ],
            ],
        ];
        foreach ($texttype as $key => $args) {
            $builder->add($key, TextType::class, $args);
        }
    }
}
