<?php

namespace Labstag\Form\Admin;

use Labstag\Entity\Render;
use Labstag\Lib\AbstractTypeLib;
use Labstag\Service\GuardService;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class RenderType extends AbstractTypeLib
{
    public function __construct(
        TranslatorInterface $translator,
        protected GuardService $guardService
    ) {
        parent::__construct($translator);
    }

    /**
     * @inheritDoc
     */
    public function buildForm(
        FormBuilderInterface $formBuilder,
        array $options
    ): void {
        $formBuilder->add(
            'name',
            TextType::class,
            [
                'label' => $this->translator->trans('render.name.label', [], 'admin.form'),
                'help'  => $this->translator->trans('render.name.help', [], 'admin.form'),
            ]
        );
        $all     = $this->guardService->getPublicRouteWithParams();
        $choices = [];
        foreach (array_keys($all) as $key) {
            $choices[$key] = $key;
        }

        $formBuilder->add(
            'url',
            ChoiceType::class,
            [
                'required' => false,
                'choices'  => $choices,
            ]
        );
        $this->setMeta($formBuilder);
        unset($options);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => Render::class,
            ]
        );
    }
}
