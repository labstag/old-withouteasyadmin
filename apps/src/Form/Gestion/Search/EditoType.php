<?php

namespace Labstag\Form\Gestion\Search;

use Labstag\Entity\Edito;
use Labstag\Lib\SearchAbstractTypeLib;
use Labstag\Search\EditoSearch;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditoType extends SearchAbstractTypeLib
{
    /**
     * @inheritDoc
     */
    public function buildForm(
        FormBuilderInterface $formBuilder,
        array $options
    ): void
    {
        $formBuilder->add(
            'title',
            TextType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('edito.title.label', [], 'gestion.search.form'),
                'help'     => $this->translator->trans('edito.title.help', [], 'gestion.search.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans('edito.title.placeholder', [], 'gestion.search.form'),
                ],
            ]
        );
        $this->addRefUser($formBuilder);
        $this->addPublished($formBuilder);
        $this->showState(
            $formBuilder,
            new Edito(),
            $this->translator->trans('edito.etape.label', [], 'gestion.search.form'),
            $this->translator->trans('edito.etape.help', [], 'gestion.search.form'),
            $this->translator->trans('edito.etape.placeholder', [], 'gestion.search.form')
        );
        parent::buildForm($formBuilder, $options);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class'      => EditoSearch::class,
                'csrf_protection' => false,
                'method'          => 'GET',
            ]
        );
    }
}
