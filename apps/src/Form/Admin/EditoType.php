<?php

namespace Labstag\Form\Admin;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Labstag\Entity\Edito;
use Labstag\Entity\User;
use Labstag\FormType\SearchableType;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditoType extends AbstractTypeLib
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        $builder->add(
            'title',
            TextType::class,
            [
                'label' => $this->translator->trans('edito.title.label', [], 'form'),
                'help'  => $this->translator->trans('edito.title.help', [], 'form'),
            ]
        );
        $builder->add(
            'published',
            DateTimeType::class,
            [
                'label'        => $this->translator->trans('edito.published.label', [], 'form'),
                'help'         => $this->translator->trans('edito.published.help', [], 'form'),
                'date_widget'  => 'single_text',
                'time_widget'  => 'single_text',
                'with_seconds' => true,
            ]
        );
        $builder->add(
            'content',
            CKEditorType::class,
            [
                'label' => $this->translator->trans('edito.content.label', [], 'form'),
                'help'  => $this->translator->trans('edito.content.help', [], 'form'),
            ]
        );
        $builder->add(
            'metaDescription',
            TextType::class,
            [
                'label' => $this->translator->trans('edito.metaDescription.label', [], 'form'),
                'help'  => $this->translator->trans('edito.metaDescription.help', [], 'form'),
            ]
        );
        $builder->add(
            'metaKeywords',
            TextType::class,
            [
                'label' => $this->translator->trans('edito.metaKeywords.label', [], 'form'),
                'help'  => $this->translator->trans('edito.metaKeywords.help', [], 'form'),
            ]
        );
        $builder->add(
            'file',
            FileType::class,
            [
                'label'    => $this->translator->trans('edito.file.label', [], 'form'),
                'help'     => $this->translator->trans('edito.file.help', [], 'form'),
                'required' => false,
                'attr'     => ['accept' => 'image/*'],
            ]
        );
        $builder->add(
            'refuser',
            SearchableType::class,
            [
                'label'    => $this->translator->trans('edito.refuser.label', [], 'form'),
                'help'     => $this->translator->trans('edito.refuser.help', [], 'form'),
                'multiple' => false,
                'class'    => User::class,
                'route'    => 'api_search_user',
            ]
        );
        unset($options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Edito::class,
            ]
        );
    }
}
