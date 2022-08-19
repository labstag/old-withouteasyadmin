<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\Block;
use Labstag\Lib\BlockAbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewBlockType extends BlockAbstractTypeLib
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        unset($options);
        $builder->add('Enregistrer', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Block::class,
            ]
        );
    }
}
