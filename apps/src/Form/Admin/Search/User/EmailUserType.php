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
        FormBuilderInterface $formBuilder,
        array $options
    ): void
    {
        $this->addRefUser($formBuilder);
        $this->showState(
            $formBuilder,
            new EmailUser(),
            $this->translator->trans('emailuser.etape.label', [], 'admin.search.form'),
            $this->translator->trans('emailuser.etape.help', [], 'admin.search.form'),
            $this->translator->trans('emailuser.etape.placeholder', [], 'admin.search.form')
        );
        parent::buildForm($formBuilder, $options);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class'      => EmailUserSearch::class,
                'csrf_protection' => false,
                'method'          => 'GET',
            ]
        );
    }
}
