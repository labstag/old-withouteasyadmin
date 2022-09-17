<?php

namespace Labstag\Form\Admin\Search;

use Labstag\Entity\Memo;
use Labstag\Lib\SearchAbstractTypeLib;
use Labstag\Search\MemoSearch;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MemoType extends SearchAbstractTypeLib
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
                'required' => false,
                'label'    => $this->translator->trans('memo.title.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('memo.title.help', [], 'admin.search.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans('memo.title.placeholder', [], 'admin.search.form'),
                ],
            ]
        );
        $this->addRefUser($formBuilder);
        $formBuilder->add(
            'dateStart',
            DateType::class,
            [
                'required' => false,
                'widget'   => 'single_text',
                'label'    => $this->translator->trans('memo.date_start.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('memo.date_start.help', [], 'admin.search.form'),
            ]
        );
        $formBuilder->add(
            'dateEnd',
            DateType::class,
            [
                'required' => false,
                'widget'   => 'single_text',
                'label'    => $this->translator->trans('memo.date_end.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('memo.date_end.help', [], 'admin.search.form'),
            ]
        );
        $this->showState(
            $formBuilder,
            new Memo(),
            $this->translator->trans('memo.etape.label', [], 'admin.search.form'),
            $this->translator->trans('memo.etape.help', [], 'admin.search.form'),
            $this->translator->trans('memo.etape.placeholder', [], 'admin.search.form')
        );
        parent::buildForm($formBuilder, $options);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class'      => MemoSearch::class,
                'csrf_protection' => false,
                'method'          => 'GET',
            ]
        );
    }
}
