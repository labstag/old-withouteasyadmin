<?php

namespace Labstag\Form\Admin\Collections\Param;

use Labstag\Service\OauthService;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class OauthType extends AbstractTypeLib
{

    protected OauthService $oauthService;

    public function __construct(
        TranslatorInterface $translator,
        OauthService $oauthService
    )
    {
        $this->oauthService = $oauthService;
        parent::__construct($translator);
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
                'label'   => $this->translator->trans('param.oauth.activate.label', [], 'admin.form'),
                'help'    => $this->translator->trans('param.oauth.activate.help', [], 'admin.form'),
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
                'label'   => $this->translator->trans('param.oauth.type.label', [], 'admin.form'),
                'help'    => $this->translator->trans('param.oauth.type.help', [], 'admin.form'),
                'choices' => $choices,
            ]
        );
        $builder->add(
            'id',
            TextType::class,
            [
                'label'    => $this->translator->trans('param.oauth.id.label', [], 'admin.form'),
                'help'     => $this->translator->trans('param.oauth.id.help', [], 'admin.form'),
                'required' => false,
            ]
        );
        $builder->add(
            'secret',
            TextType::class,
            [
                'label'    => $this->translator->trans('param.oauth.secret.label', [], 'admin.form'),
                'help'     => $this->translator->trans('param.oauth.secret.help', [], 'admin.form'),
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
