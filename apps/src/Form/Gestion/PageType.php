<?php

namespace Labstag\Form\Gestion;

use Labstag\Entity\Page;
use Labstag\Lib\AbstractTypeLib;
use Labstag\Repository\PageRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageType extends AbstractTypeLib
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
            'name',
            TextType::class,
            [
                'label' => $this->translator->trans('page.name.label', [], 'gestion.form'),
                'help'  => $this->translator->trans('page.name.help', [], 'gestion.form'),
            ]
        );
        $this->addParagraph(
            $formBuilder,
            [
                'add'    => 'gestion_page_paragraph_add',
                'edit'   => 'gestion_page_paragraph_show',
                'delete' => 'gestion_page_paragraph_delete',
            ]
        );
        $formBuilder->add(
            'password',
            TextType::class,
            [
                'label'    => $this->translator->trans('page.password.label', [], 'gestion.form'),
                'help'     => $this->translator->trans('page.password.help', [], 'gestion.form'),
                'required' => false,
            ]
        );
        $formBuilder->add(
            'slug',
            TextType::class,
            [
                'label'    => $this->translator->trans('page.slug.label', [], 'gestion.form'),
                'help'     => $this->translator->trans('page.slug.help', [], 'gestion.form'),
                'required' => false,
            ]
        );

        $formBuilder->add(
            'parent',
            EntityType::class,
            [
                'required'      => false,
                'class'         => Page::class,
                'query_builder' => static fn (PageRepository $pageRepository) => $pageRepository->formType($options),
            ]
        );
        $this->setMeta($formBuilder);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => Page::class,
            ]
        );
    }
}
