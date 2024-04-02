<?php

namespace Labstag\Form\Gestion\Search;

use Labstag\FormType\FlagCountryType;
use Labstag\Lib\SearchAbstractTypeLib;
use Labstag\Search\GeoCodeSearch;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GeoCodeType extends SearchAbstractTypeLib
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
            'countrycode',
            FlagCountryType::class,
            ['required' => false]
        );
        $this->setTextType($formBuilder);
        parent::buildForm($formBuilder, $options);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class'      => GeoCodeSearch::class,
                'csrf_protection' => false,
                'method'          => 'GET',
            ]
        );
    }

    protected function setTextType(FormBuilderInterface $formBuilder): void
    {
        $texttype = [
            'postalcode'    => [
                'required' => false,
                'label'    => $this->translator->trans('geocode.postalcode.label', [], 'gestion.search.form'),
                'help'     => $this->translator->trans('geocode.postalcode.help', [], 'gestion.search.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans(
                        'geocode.postalcode.placeholder',
                        [],
                        'gestion.search.form'
                    ),
                ],
            ],
            'placename'     => [
                'required' => false,
                'label'    => $this->translator->trans('geocode.placename.label', [], 'gestion.search.form'),
                'help'     => $this->translator->trans('geocode.placename.help', [], 'gestion.search.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans(
                        'geocode.placename.placeholder',
                        [],
                        'gestion.search.form'
                    ),
                ],
            ],
            'statename'     => [
                'required' => false,
                'label'    => $this->translator->trans('geocode.statename.label', [], 'gestion.search.form'),
                'help'     => $this->translator->trans('geocode.statename.help', [], 'gestion.search.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans(
                        'geocode.statename.placeholder',
                        [],
                        'gestion.search.form'
                    ),
                ],
            ],
            'provincename'  => [
                'required' => false,
                'label'    => $this->translator->trans('geocode.provincename.label', [], 'gestion.search.form'),
                'help'     => $this->translator->trans('geocode.provincename.help', [], 'gestion.search.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans(
                        'geocode.provincename.placeholder',
                        [],
                        'gestion.search.form'
                    ),
                ],
            ],
            'communityname' => [
                'required' => false,
                'label'    => $this->translator->trans('geocode.communityname.label', [], 'gestion.search.form'),
                'help'     => $this->translator->trans('geocode.communityname.help', [], 'gestion.search.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans(
                        'geocode.communityname.placeholder',
                        [],
                        'gestion.search.form'
                    ),
                ],
            ],
        ];
        foreach ($texttype as $key => $args) {
            $formBuilder->add($key, TextType::class, $args);
        }
    }
}
