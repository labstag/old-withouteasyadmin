<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\History;
use Labstag\Entity\User;
use Labstag\FormType\SearchableType;
use Labstag\FormType\WysiwygType;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HistoryType extends AbstractTypeLib
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
        $formBuilder->add(
            'summary',
            WysiwygType::class,
            [
                'label' => $this->translator->trans('history.summary.label', [], 'admin.form'),
                'help'  => $this->translator->trans('history.summary.help', [], 'admin.form'),
            ]
        );
        $this->addParagraph(
            $formBuilder,
            [
                'add'    => 'admin_history_paragraph_add',
                'edit'   => 'admin_history_paragraph_show',
                'delete' => 'admin_history_paragraph_delete',
            ]
        );
        $formBuilder->add(
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
        $this->setMeta($formBuilder);
        unset($options);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => History::class,
            ]
        );
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
