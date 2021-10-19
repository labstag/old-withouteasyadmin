<?php

namespace Labstag\Form\Admin\User;

use Doctrine\DBAL\Types\TextType;
use Labstag\Entity\Groupe;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupeType extends AbstractTypeLib
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
                'label' => $this->translator->trans('groupe.name.label', [], 'admin.form'),
                'help'  => $this->translator->trans('groupe.name.help', [], 'admin.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('groupe.name.placeholder', [], 'admin.form'),
                ],
            ]
        );
        $builder->add(
            'code',
            TextType::class,
            [
                'label' => $this->translator->trans('groupe.code.label', [], 'admin.form'),
                'help'  => $this->translator->trans('groupe.code.help', [], 'admin.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('groupe.code.placeholder', [], 'admin.form'),
                ],
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Groupe::class,
            ]
        );
    }
}
