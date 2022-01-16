<?php

namespace Labstag\Lib;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Workflow\Registry;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class SearchAbstractTypeLib extends AbstractType
{
    public function __construct(protected Registry $workflows, protected TranslatorInterface $translator)
    {
    }

    /**
     * @inheritdoc
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        $builder->add(
            'limit',
            NumberType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('form.limit.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('form.limit.help', [], 'admin.search.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans('form.limit.placeholder', [], 'admin.search.form'),
                ],
            ]
        );
        $builder->add(
            'submit',
            SubmitType::class,
            [
                'label' => $this->translator->trans('form.submit', [], 'admin.search.form'),
                'attr'  => ['name' => ''],
            ]
        );
        $builder->add(
            'reset',
            ResetType::class,
            [
                'label' => $this->translator->trans('form.reset', [], 'admin.search.form'),
                'attr'  => ['name' => ''],
            ]
        );
        unset($options);
    }
}
