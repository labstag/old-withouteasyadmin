<?php

namespace Labstag\Form\Admin;

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
                'label' => $this->translator->trans('memo.title.label', [], 'admin.form'),
                'help'  => $this->translator->trans('memo.title.help', [], 'admin.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('memo.title.placeholder', [], 'admin.form'),
                ],
            ]
        );
        $this->setContent($formBuilder);
        $this->addParagraph(
            $formBuilder,
            [
                'add'    => 'admin_memo_paragraph_add',
                'edit'   => 'admin_memo_paragraph_show',
                'delete' => 'admin_memo_paragraph_delete',
            ]
        );
        $formBuilder->add(
            'date_start',
            DateTimeType::class,
            [
                'label'        => $this->translator->trans('memo.date_start.label', [], 'admin.form'),
                'help'         => $this->translator->trans('memo.date_start.help', [], 'admin.form'),
                'date_widget'  => 'single_text',
                'time_widget'  => 'single_text',
                'with_seconds' => true,
            ]
        );
        $formBuilder->add(
            'date_end',
            DateTimeType::class,
            [
                'label'        => $this->translator->trans('memo.date_end.label', [], 'admin.form'),
                'help'         => $this->translator->trans('memo.date_end.help', [], 'admin.form'),
                'date_widget'  => 'single_text',
                'time_widget'  => 'single_text',
                'with_seconds' => true,
            ]
        );

        $formBuilder->add(
            'file',
            FileType::class,
            [
                'label'    => $this->translator->trans('memo.file.label', [], 'admin.form'),
                'help'     => $this->translator->trans('memo.file.help', [], 'admin.form'),
                'required' => false,
                'attr'     => ['accept' => 'image/*'],
            ]
        );
        $formBuilder->add(
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
        unset($options);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => Memo::class,
            ]
        );
    }
}
