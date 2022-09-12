<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\Block\Custom;
use Labstag\Entity\Layout;
use Labstag\Lib\AbstractTypeLib;
use Labstag\Repository\Block\CustomRepository;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Labstag\Service\GuardService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class NewLayoutType extends AbstractTypeLib
{
    public function __construct(
        TranslatorInterface $translator,
        protected GuardService $service
    )
    {
        parent::__construct($translator);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        unset($options);
        $builder->add(
            'custom',
            EntityType::class,
            [
                'class'         => Custom::class,
                'query_builder' => fn (CustomRepository $er) => $er->formType(),
            ]
        );
        $builder->add('Enregistrer', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Layout::class,
            ]
        );
    }
}
