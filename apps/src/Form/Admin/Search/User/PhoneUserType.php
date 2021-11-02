<?php

namespace Labstag\Form\Admin\Search\User;

use Labstag\Entity\PhoneUser;
use Labstag\Entity\User;
use Labstag\FormType\FlagCountryType;
use Labstag\FormType\SearchableType;
use Labstag\Lib\SearchAbstractTypeLib;
use Labstag\Search\User\PhoneUserSearch;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PhoneUserType extends SearchAbstractTypeLib
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
            'country',
            FlagCountryType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('phoneuser.country.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('phoneuser.country.help', [], 'admin.search.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans('phoneuser.country.placeholder', [], 'admin.search.form'),
                ],
            ]
        );
        $builder->add(
            'refuser',
            SearchableType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('phoneuser.refuser.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('phoneuser.refuser.help', [], 'admin.search.form'),
                'multiple' => false,
                'class'    => User::class,
                'route'    => 'api_search_user',
                'attr'     => [
                    'placeholder' => $this->translator->trans(
                        'phoneuser.refuser.placeholder',
                        [],
                        'admin.search.form'
                    ),
                ],
            ]
        );
        $workflow   = $this->workflows->get(new PhoneUser());
        $definition = $workflow->getDefinition();
        $places     = $definition->getPlaces();
        $builder->add(
            'etape',
            ChoiceType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('linkuser.etape.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('linkuser.etape.help', [], 'admin.search.form'),
                'choices'  => $places,
                'attr'     => [
                    'placeholder' => $this->translator->trans('linkuser.etape.placeholder', [], 'admin.search.form'),
                ],
            ]
        );
        parent::buildForm($builder, $options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class'      => PhoneUserSearch::class,
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
