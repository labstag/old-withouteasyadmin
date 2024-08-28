<?php

namespace Labstag\Form\Gestion;

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
                'label' => $this->translator->trans('history.summary.label', [], 'gestion.form'),
                'help'  => $this->translator->trans('history.summary.help', [], 'gestion.form'),
            ]
        );
        $this->addParagraph(
            $formBuilder,
            [
                'add'    => 'gestion_history_paragraph_add',
                'edit'   => 'gestion_history_paragraph_show',
                'delete' => 'gestion_history_paragraph_delete',
            ]
        );
        $formBuilder->add(
            'refuser',
            SearchableType::class,
            [
                'label'    => $this->translator->trans('history.refuser.label', [], 'gestion.form'),
                'help'     => $this->translator->trans('history.refuser.help', [], 'gestion.form'),
                'multiple' => false,
                'class'    => User::class,
                'route'    => 'api_search_user',
                'attr'     => [
                    'placeholder' => $this->translator->trans('history.refuser.placeholder', [], 'gestion.form'),
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

    protected function setTextType(FormBuilderInterface $formBuilder): void
    {
        $texttype = [
            'name' => [
                'label' => $this->translator->trans('history.name.label', [], 'gestion.form'),
                'help'  => $this->translator->trans('history.name.help', [], 'gestion.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('history.name.placeholder', [], 'gestion.form'),
                ],
            ],
            'slug' => [
                'label'    => $this->translator->trans('history.slug.label', [], 'gestion.form'),
                'help'     => $this->translator->trans('history.slug.help', [], 'gestion.form'),
                'required' => false,
                'attr'     => [
                    'placeholder' => $this->translator->trans('history.slug.placeholder', [], 'gestion.form'),
                ],
            ],
        ];
        foreach ($texttype as $key => $args) {
            $formBuilder->add($key, TextType::class, $args);
        }
    }
}
