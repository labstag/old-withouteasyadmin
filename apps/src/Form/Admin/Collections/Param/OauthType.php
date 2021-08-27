<?php

namespace Labstag\Form\Admin\Collections\Param;

use Labstag\Service\OauthService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OauthType extends AbstractType
{

    protected OauthService $oauthService;

    public function __construct(OauthService $oauthService)
    {
        $this->oauthService = $oauthService;
    }

    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        $builder->add(
            'activate',
            ChoiceType::class,
            [
                'choices' => [
                    'Non' => '0',
                    'Oui' => '1',
                ],
            ]
        );
        $types   = $this->oauthService->getTypes();
        $choices = [];
        foreach ($types as $type) {
            $choices[$type] = $type;
        }

        ksort($choices);
        $builder->add(
            'type',
            ChoiceType::class,
            ['choices' => $choices]
        );
        $builder->add('id', TextType::class, ['required' => false]);
        $builder->add('secret', TextType::class, ['required' => false]);
        unset($options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // Configure your form options here
        $resolver->setDefaults(
            []
        );
    }
}
