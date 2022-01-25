<?php

namespace Labstag\Lib;

use Labstag\Entity\Category;
use Labstag\Entity\User;
use Labstag\FormType\SearchableType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Workflow\Registry;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class SearchAbstractTypeLib extends AbstractType
{
    public function __construct(protected Registry $workflows, protected TranslatorInterface $translator)
    {
    }

    protected function addRefCategory($builder)
    {
        $builder->add(
            'refcategory',
            SearchableType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('refcategory.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('refcategory.help', [], 'admin.search.form'),
                'multiple' => false,
                'class'    => Category::class,
                'route'    => 'api_search_category',
                'attr'     => [
                    'placeholder' => $this->translator->trans('refcategory.placeholder', [], 'admin.search.form'),
                ],
            ]
        );
    }

    public function getBlockPrefix(): string
    {
        return '';
    }

    protected function addRefUser($builder)
    {
        $builder->add(
            'refuser',
            SearchableType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('refuser.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('refuser.help', [], 'admin.search.form'),
                'multiple' => false,
                'class'    => User::class,
                'route'    => 'api_search_user',
                'attr'     => [
                    'placeholder' => $this->translator->trans('refuser.placeholder', [], 'admin.search.form'),
                ],
            ]
        );
    }

    protected function addName($builder)
    {
        $builder->add(
            'name',
            TextType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('name.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('name.help', [], 'admin.search.form'),
                'attr'     => ['placeholder' => $this->translator->trans('.name.placeholder', [], 'admin.search.form')],
            ]
        );
    }

    protected function addPublished($builder)
    {
        $builder->add(
            'published',
            DateType::class,
            [
                'required' => false,
                'widget'   => 'single_text',
                'label'    => $this->translator->trans('published.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('published.help', [], 'admin.search.form'),
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        $builder->add(
            'limit',
            NumberType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('form.limit.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('form.limit.help', [], 'admin.search.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans('form.limit.placeholder', [], 'admin.search.form'),
                ],
            ]
        );
        $builder->add(
            'submit',
            SubmitType::class,
            [
                'label' => $this->translator->trans('form.submit', [], 'admin.search.form'),
                'attr'  => ['name' => ''],
            ]
        );
        $builder->add(
            'reset',
            ResetType::class,
            [
                'label' => $this->translator->trans('form.reset', [], 'admin.search.form'),
                'attr'  => ['name' => ''],
            ]
        );
        unset($options);
    }

    protected function showState(
        $builder,
        $entityclass,
        $label,
        $help,
        $placeholder
    )
    {
        $workflow   = $this->workflows->get($entityclass);
        $definition = $workflow->getDefinition();
        $places     = $definition->getPlaces();
        $builder->add(
            'etape',
            ChoiceType::class,
            [
                'required' => false,
                'label'    => $label,
                'help'     => $help,
                'choices'  => $places,
                'attr'     => ['placeholder' => $placeholder],
            ]
        );
    }
}
