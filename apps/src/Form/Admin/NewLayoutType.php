<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\Block\Custom;
use Labstag\Entity\Layout;
use Labstag\Lib\AbstractTypeLib;
use Labstag\Repository\Block\CustomRepository;
use Labstag\Service\GuardService;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class NewLayoutType extends AbstractTypeLib
{
    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        unset($options);
        $formBuilder->add(
            'custom',
            EntityType::class,
            [
                'class'         => Custom::class,
                'query_builder' => static fn (CustomRepository $customRepository) => $customRepository->formType(),
            ]
        );
        $formBuilder->add('Enregistrer', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => Layout::class,
            ]
        );
    }
}
