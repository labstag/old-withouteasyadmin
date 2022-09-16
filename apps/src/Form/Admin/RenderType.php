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
        protected GuardService $service
    )
    {
        parent::__construct($translator);
    }

    /**
     * @inheritDoc
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        $builder->add(
            'name',
            TextType::class,
            [
                'label' => $this->translator->trans('render.name.label', [], 'admin.form'),
                'help'  => $this->translator->trans('render.name.help', [], 'admin.form'),
            ]
        );
        $all     = $this->service->getPublicRouteWithParams();
        $choices = [];
        foreach (array_keys($all) as $key) {
            $choices[$key] = $key;
        }

        $builder->add(
            'url',
            ChoiceType::class,
            [
                'required' => false,
                'multiple' => true,
                'choices'  => $choices,
            ]
        );
        $this->setMeta($builder);
        unset($options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Render::class,
            ]
        );
    }
}
