<?php

namespace Labstag\Form\Admin\Search;

use Labstag\Entity\Bookmark;
use Labstag\Lib\SearchAbstractTypeLib;
use Labstag\Search\PageSearch;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageType extends SearchAbstractTypeLib
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
            'name',
            TextType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('page.name.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('page.name.help', [], 'admin.search.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans('page.name.placeholder', [], 'admin.search.form'),
                ],
            ]
        );
        $workflow   = $this->workflows->get(new Bookmark());
        $definition = $workflow->getDefinition();
        $places     = $definition->getPlaces();
        $builder->add(
            'etape',
            ChoiceType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('page.etape.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('page.etape.help', [], 'admin.search.form'),
                'choices'  => $places,
                'attr'     => [
                    'placeholder' => $this->translator->trans('page.etape.placeholder', [], 'admin.search.form'),
                ],
            ]
        );
        parent::buildForm($builder, $options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class'      => PageSearch::class,
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
