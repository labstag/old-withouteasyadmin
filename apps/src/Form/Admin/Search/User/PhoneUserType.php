<?php

namespace Labstag\Form\Admin\Search\User;

use Labstag\Entity\PhoneUser;
use Labstag\FormType\FlagCountryType;
use Labstag\Lib\SearchAbstractTypeLib;
use Labstag\Search\User\PhoneUserSearch;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PhoneUserType extends SearchAbstractTypeLib
{
    /**
     * @inheritDoc
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        $builder->add(
            'country',
            FlagCountryType::class
        );
        $this->addRefUser($builder);
        $this->showState(
            $builder,
            new PhoneUser(),
            $this->translator->trans('phoneuser.etape.label', [], 'admin.search.form'),
            $this->translator->trans('phoneuser.etape.help', [], 'admin.search.form'),
            $this->translator->trans('phoneuser.etape.placeholder', [], 'admin.search.form')
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
}
