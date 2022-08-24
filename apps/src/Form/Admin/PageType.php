<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\Page;
use Labstag\Lib\AbstractTypeLib;
use Labstag\Repository\PageRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageType extends AbstractTypeLib
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
            'name',
            TextType::class,
            [
                'label' => $this->translator->trans('page.name.label', [], 'admin.form'),
                'help'  => $this->translator->trans('page.name.help', [], 'admin.form'),
            ]
        );
        $this->addParagraph(
            $builder,
            [
                'add'    => 'admin_page_paragraph_add',
                'edit'   => 'admin_page_paragraph_show',
                'delete' => 'admin_page_paragraph_delete',
            ]
        );
        $builder->add(
            'password',
            TextType::class,
            [
                'label'    => $this->translator->trans('page.password.label', [], 'admin.form'),
                'help'     => $this->translator->trans('page.password.help', [], 'admin.form'),
                'required' => false,
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

        $builder->add(
            'parent',
            EntityType::class,
            [
                'required'      => false,
                'class'         => Page::class,
                'query_builder' => fn (PageRepository $er) => $er->formType($options),
            ]
        );

        $builder->add(
            'front',
            CheckboxType::class,
            [
                'label' => $this->translator->trans('page.front.label', [], 'admin.form'),
                'help'  => $this->translator->trans('page.front.help', [], 'admin.form'),
            ]
        );

        $choices = $this->templatePageService->getChoices();

        $builder->add(
            'function',
            ChoiceType::class,
            ['choices' => $choices]
        );
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
