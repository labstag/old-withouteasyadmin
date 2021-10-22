<?php

namespace Labstag\Form\Admin\Search;

use Labstag\Entity\NoteInterne;
use Labstag\Entity\User;
use Labstag\FormType\SearchableType;
use Labstag\Lib\SearchAbstractTypeLib;
use Labstag\Search\NoteInterneSearch;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NoteInterneType extends SearchAbstractTypeLib
{
    /**
     * @inheritdoc
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        $builder->add(
            'title',
            TextType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('noteinterne.title.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('noteinterne.title.help', [], 'admin.search.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans('noteinterne.title.placeholder', [], 'admin.search.form'),
                ],
            ]
        );
        $builder->add(
            'refuser',
            SearchableType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('noteinterne.refuser.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('noteinterne.refuser.help', [], 'admin.search.form'),
                'multiple' => false,
                'class'    => User::class,
                'route'    => 'api_search_user',
                'attr'     => [
                    'placeholder' => $this->translator->trans(
                        'noteinterne.refuser.placeholder',
                        [],
                        'admin.search.form'
                    ),
                ],
            ]
        );
        $builder->add(
            'dateDebut',
            DateType::class,
            [
                'required' => false,
                'widget'   => 'single_text',
                'label'    => $this->translator->trans('noteinterne.date_debut.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('noteinterne.date_debut.help', [], 'admin.search.form'),
            ]
        );
        $builder->add(
            'dateFin',
            DateType::class,
            [
                'required' => false,
                'widget'   => 'single_text',
                'label'    => $this->translator->trans('noteinterne.date_fin.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('noteinterne.date_fin.help', [], 'admin.search.form'),
            ]
        );
        $workflow   = $this->workflows->get(new NoteInterne());
        $definition = $workflow->getDefinition();
        $places     = $definition->getPlaces();
        $builder->add(
            'etape',
            ChoiceType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('noteinterne.etape.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('noteinterne.etape.help', [], 'admin.search.form'),
                'choices'  => $places,
                'attr'     => [
                    'placeholder' => $this->translator->trans(
                        'noteinterne.etape.placeholder',
                        [],
                        'admin.search.form'
                    ),
                ],
            ]
        );
        parent::buildForm($builder, $options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class'      => NoteInterneSearch::class,
                'csrf_protection' => false,
                'method'          => 'GET',
            ]
        );
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
