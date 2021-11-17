<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\Page;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageType extends AbstractTypeLib
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
                'label' => $this->translator->trans('page.name.label', [], 'admin.form'),
                'help'  => $this->translator->trans('page.name.help', [], 'admin.form'),
            ]
        );
        $builder->add(
            'slug',
            TextType::class,
            [
                'label'    => $this->translator->trans('page.slug.label', [], 'admin.form'),
                'help'     => $this->translator->trans('page.slug.help', [], 'admin.form'),
                'required' => false,
            ]
        );

        $choices = $this->templatePageService->getChoices();

        dump($choices);

        $builder->add(
            'isAttending',
            ChoiceType::class,
            [
                'mapped'  => false,
                'choices' => $choices,
            ]
        );
        unset($options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Page::class,
            ]
        );
    }
}
