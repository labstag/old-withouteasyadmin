<?php

namespace Labstag\Form\Admin;

use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;

abstract class LienType extends AbstractTypeLib
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
            'name',
            TextType::class,
            [
                'label' => $this->translator->trans('lien.name.label', [], 'admin.form'),
                'help'  => $this->translator->trans('lien.name.help', [], 'admin.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('lien.name.placeholder', [], 'admin.form'),
                ],
            ]
        );
        $builder->add(
            'address',
            UrlType::class,
            [
                'label' => $this->translator->trans('lien.address.label', [], 'admin.form'),
                'help'  => $this->translator->trans('lien.address.help', [], 'admin.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('lien.address.placeholder', [], 'admin.form'),
                ],
            ]
        );
    }
}
