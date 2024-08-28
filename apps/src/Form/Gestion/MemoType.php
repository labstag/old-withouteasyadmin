<?php

namespace Labstag\Form\Gestion;

use Labstag\Entity\Memo;
use Labstag\Entity\User;
use Labstag\FormType\SearchableType;
use Labstag\FormType\UploadType;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
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
                'label' => $this->translator->trans('memo.title.label', [], 'gestion.form'),
                'help'  => $this->translator->trans('memo.title.help', [], 'gestion.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('memo.title.placeholder', [], 'gestion.form'),
                ],
            ]
        );
        $this->setContent($formBuilder);
        $this->addParagraph(
            $formBuilder,
            [
                'add'    => 'gestion_memo_paragraph_add',
                'edit'   => 'gestion_memo_paragraph_show',
                'delete' => 'gestion_memo_paragraph_delete',
            ]
        );
        $formBuilder->add(
            'date_start',
            DateTimeType::class,
            [
                'label'        => $this->translator->trans('memo.date_start.label', [], 'gestion.form'),
                'help'         => $this->translator->trans('memo.date_start.help', [], 'gestion.form'),
                'date_widget'  => 'single_text',
                'time_widget'  => 'single_text',
                'with_seconds' => true,
            ]
        );
        $formBuilder->add(
            'date_end',
            DateTimeType::class,
            [
                'label'        => $this->translator->trans('memo.date_end.label', [], 'gestion.form'),
                'help'         => $this->translator->trans('memo.date_end.help', [], 'gestion.form'),
                'date_widget'  => 'single_text',
                'time_widget'  => 'single_text',
                'with_seconds' => true,
            ]
        );

        $formBuilder->add(
            'file',
            UploadType::class,
            [
                'label'    => $this->translator->trans('memo.file.label', [], 'gestion.form'),
                'help'     => $this->translator->trans('memo.file.help', [], 'gestion.form'),
                'required' => false,
                'attr'     => ['accept' => 'image/*'],
            ]
        );
        $formBuilder->add(
            'refuser',
            SearchableType::class,
            [
                'label'    => $this->translator->trans('memo.refuser.label', [], 'gestion.form'),
                'help'     => $this->translator->trans('memo.refuser.help', [], 'gestion.form'),
                'multiple' => false,
                'class'    => User::class,
                'route'    => 'api_search_user',
                'attr'     => [
                    'placeholder' => $this->translator->trans('memo.refuser.placeholder', [], 'gestion.form'),
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
