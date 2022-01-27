<?php

namespace Labstag\Form\Admin\Search\User;

use Labstag\Entity\EmailUser;
use Labstag\Lib\SearchAbstractTypeLib;
use Labstag\Search\User\EmailUserSearch;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmailUserType extends SearchAbstractTypeLib
{
    /**
     * @inheritDoc
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        $this->addRefUser($builder);
        $this->showState(
            $builder,
            new EmailUser(),
            $this->translator->trans('emailuser.etape.label', [], 'admin.search.form'),
            $this->translator->trans('emailuser.etape.help', [], 'admin.search.form'),
            $this->translator->trans('emailuser.etape.placeholder', [], 'admin.search.form')
        );
        parent::buildForm($builder, $options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class'      => EmailUserSearch::class,
                'csrf_protection' => false,
                'method'          => 'GET',
            ]
        );
    }
}
