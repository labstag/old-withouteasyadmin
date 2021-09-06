<?php

namespace Labstag\Form\Admin\Collections\Param;

use Labstag\Service\OauthService;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OauthType extends AbstractTypeLib
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
                'label'   => $this->translator->trans('param.oauth.activate.label', [], 'form'),
                'help'    => $this->translator->trans('param.oauth.activate.help', [], 'form'),
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
            [
                'label'   => $this->translator->trans('param.oauth.type.label', [], 'form'),
                'help'    => $this->translator->trans('param.oauth.type.help', [], 'form'),
                'choices' => $choices,
            ]
        );
        $builder->add(
            'id',
            TextType::class,
            [
                'label'    => $this->translator->trans('param.oauth.id.label', [], 'form'),
                'help'     => $this->translator->trans('param.oauth.id.help', [], 'form'),
                'required' => false,
            ]
        );
        $builder->add(
            'secret',
            TextType::class,
            [
                'label'    => $this->translator->trans('param.oauth.secret.label', [], 'form'),
                'help'     => $this->translator->trans('param.oauth.secret.help', [], 'form'),
                'required' => false,
            ]
        );
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
