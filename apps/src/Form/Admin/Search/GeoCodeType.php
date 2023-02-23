<?php

namespace Labstag\Form\Admin\Search;

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
                'label'    => $this->translator->trans('geocode.postalcode.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('geocode.postalcode.help', [], 'admin.search.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans(
                        'geocode.postalcode.placeholder',
                        [],
                        'admin.search.form'
                    ),
                ],
            ],
            'placename'     => [
                'required' => false,
                'label'    => $this->translator->trans('geocode.placename.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('geocode.placename.help', [], 'admin.search.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans(
                        'geocode.placename.placeholder',
                        [],
                        'admin.search.form'
                    ),
                ],
            ],
            'statename'     => [
                'required' => false,
                'label'    => $this->translator->trans('geocode.statename.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('geocode.statename.help', [], 'admin.search.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans(
                        'geocode.statename.placeholder',
                        [],
                        'admin.search.form'
                    ),
                ],
            ],
            'provincename'  => [
                'required' => false,
                'label'    => $this->translator->trans('geocode.provincename.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('geocode.provincename.help', [], 'admin.search.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans(
                        'geocode.provincename.placeholder',
                        [],
                        'admin.search.form'
                    ),
                ],
            ],
            'communityname' => [
                'required' => false,
                'label'    => $this->translator->trans('geocode.communityname.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('geocode.communityname.help', [], 'admin.search.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans(
                        'geocode.communityname.placeholder',
                        [],
                        'admin.search.form'
                    ),
                ],
            ],
        ];
        foreach ($texttype as $key => $args) {
            $formBuilder->add($key, TextType::class, $args);
        }
    }
}
