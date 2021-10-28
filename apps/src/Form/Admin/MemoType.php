<?php

namespace Labstag\Form\Admin;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Labstag\Entity\Memo;
use Labstag\Entity\User;
use Labstag\FormType\SearchableType;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MemoType extends AbstractTypeLib
{
    /**
     * @inheritdoc
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        unset($options);
        $builder->add(
            'title',
            TextType::class,
            [
                'label' => $this->translator->trans('memo.title.label', [], 'admin.form'),
                'help'  => $this->translator->trans('memo.title.help', [], 'admin.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('memo.title.placeholder', [], 'admin.form'),
                ],
            ]
        );
        $builder->add(
            'content',
            CKEditorType::class,
            [
                'label' => $this->translator->trans('memo.content.label', [], 'admin.form'),
                'help'  => $this->translator->trans('memo.content.help', [], 'admin.form'),
            ]
        );
        $builder->add(
            'date_debut',
            DateTimeType::class,
            [
                'label'        => $this->translator->trans('memo.date_debut.label', [], 'admin.form'),
                'help'         => $this->translator->trans('memo.date_debut.help', [], 'admin.form'),
                'date_widget'  => 'single_text',
                'time_widget'  => 'single_text',
                'with_seconds' => true,
            ]
        );
        $builder->add(
            'date_fin',
            DateTimeType::class,
            [
                'label'        => $this->translator->trans('memo.date_fin.label', [], 'admin.form'),
                'help'         => $this->translator->trans('memo.date_fin.help', [], 'admin.form'),
                'date_widget'  => 'single_text',
                'time_widget'  => 'single_text',
                'with_seconds' => true,
            ]
        );

        $builder->add(
            'file',
            FileType::class,
            [
                'label'    => $this->translator->trans('memo.file.label', [], 'admin.form'),
                'help'     => $this->translator->trans('memo.file.help', [], 'admin.form'),
                'required' => false,
                'attr'     => ['accept' => 'image/*'],
            ]
        );
        $builder->add(
            'refuser',
            SearchableType::class,
            [
                'label'    => $this->translator->trans('memo.refuser.label', [], 'admin.form'),
                'help'     => $this->translator->trans('memo.refuser.help', [], 'admin.form'),
                'multiple' => false,
                'class'    => User::class,
                'route'    => 'api_search_user',
                'attr'     => [
                    'placeholder' => $this->translator->trans('memo.refuser.placeholder', [], 'admin.form'),
                ],
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Memo::class,
            ]
        );
    }
}
