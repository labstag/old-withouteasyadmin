<?php

namespace Labstag\Form\Admin\Menu;

use Labstag\Lib\AbstractTypeLib;
use Labstag\Service\GuardService;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class DataType extends AbstractTypeLib
{
    public function __construct(
        TranslatorInterface $translator,
        protected GuardService $service
    )
    {
        parent::__construct($translator);
    }

    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        unset($options);

        $all     = $this->service->allRoutes();
        $choices = [];
        foreach (array_keys($all) as $key) {
            $choices[$key] = $key;
        }

        $builder->add(
            'route',
            ChoiceType::class,
            [
                'choices'  => $choices,
                'label'    => $this->translator->trans('menu.data.route.name.label', [], 'admin.form'),
                'help'     => $this->translator->trans('menu.data.route.name.help', [], 'admin.form'),
                'required' => false,
                'attr'     => [
                    'placeholder' => $this->translator->trans('menu.data.route.name.placeholder', [], 'admin.form'),
                ],
            ]
        );
        $builder->add(
            'param',
            TextType::class,
            [
                'label'    => $this->translator->trans('menu.data.route.param.label', [], 'admin.form'),
                'help'     => $this->translator->trans('menu.data.route.param.help', [], 'admin.form'),
                'required' => false,
                'attr'     => [
                    'placeholder' => $this->translator->trans('menu.data.route.param.placeholder', [], 'admin.form'),
                ],
            ]
        );
        $builder->add(
            'url',
            TextType::class,
            [
                'label'    => $this->translator->trans('menu.data.route.url.label', [], 'admin.form'),
                'help'     => $this->translator->trans('menu.data.route.url.help', [], 'admin.form'),
                'required' => false,
                'attr'     => [
                    'placeholder' => $this->translator->trans('menu.data.route.url.placeholder', [], 'admin.form'),
                ],
            ]
        );
        $builder->add(
            'target',
            ChoiceType::class,
            [
                'label'    => $this->translator->trans('menu.data.route.target.label', [], 'admin.form'),
                'help'     => $this->translator->trans('menu.data.route.target.help', [], 'admin.form'),
                'required' => false,
                'choices'  => [
                    ''        => '',
                    '_self'   => '_self',
                    '_blank'  => '_blank',
                    '_parent' => '_parent',
                    '_top'    => '_top',
                ],
                'attr'     => [
                    'placeholder' => $this->translator->trans('menu.data.route.target.placeholder', [], 'admin.form'),
                ],
            ]
        );
    }
}
