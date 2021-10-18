<?php

namespace Labstag\Form\Admin\Search;

use Labstag\Entity\NoteInterne;
use Labstag\Entity\User;
use Labstag\FormType\SearchableType;
use Labstag\Lib\AbstractTypeLib;
use Labstag\Search\NoteInterneSearch;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Workflow\Registry;
use Symfony\Contracts\Translation\TranslatorInterface;

class NoteInterneType extends AbstractTypeLib
{

    protected Registry $workflows;

    public function __construct(
        Registry $workflows,
        TranslatorInterface $translator
    )
    {
        $this->workflows = $workflows;
        parent::__construct($translator);
    }

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
            ]
        );
        $builder->add(
            'submit',
            SubmitType::class,
            [
                'attr' => ['name' => ''],
            ]
        );
        $builder->add(
            'reset',
            ResetType::class,
            [
                'attr' => ['name' => ''],
            ]
        );
        unset($options);
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
