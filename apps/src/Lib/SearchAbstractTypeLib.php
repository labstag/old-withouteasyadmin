<?php

namespace Labstag\Lib;

use Labstag\Entity\Category;
use Labstag\Entity\User;
use Labstag\FormType\SearchableType;
use Labstag\Interfaces\EntityInterface;
use Labstag\Service\WorkflowService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Workflow\WorkflowInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class SearchAbstractTypeLib extends AbstractType
{
    public function __construct(
        protected ParameterBagInterface $parameterBag,
        protected WorkflowService $workflowService,
        protected TranslatorInterface $translator
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function buildForm(
        FormBuilderInterface $formBuilder,
        array $options
    ): void
    {
        $formBuilder->add(
            'limit',
            NumberType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('form.limit.label', [], 'gestion.search.form'),
                'help'     => $this->translator->trans('form.limit.help', [], 'gestion.search.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans('form.limit.placeholder', [], 'gestion.search.form'),
                ],
            ]
        );
        $formBuilder->add(
            'submit',
            SubmitType::class,
            [
                'label' => $this->translator->trans('form.submit', [], 'gestion.search.form'),
                'attr'  => ['name' => ''],
            ]
        );
        $formBuilder->add(
            'reset',
            ResetType::class,
            [
                'label' => $this->translator->trans('form.reset', [], 'gestion.search.form'),
                'attr'  => ['name' => ''],
            ]
        );
        unset($options);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }

    protected function addName(FormBuilderInterface $formBuilder): void
    {
        $formBuilder->add(
            'name',
            TextType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('name.label', [], 'gestion.search.form'),
                'help'     => $this->translator->trans('name.help', [], 'gestion.search.form'),
                'attr'     => ['placeholder' => $this->translator->trans('.name.placeholder', [], 'gestion.search.form')],
            ]
        );
    }

    protected function addPublished(FormBuilderInterface $formBuilder): void
    {
        $formBuilder->add(
            'published',
            DateType::class,
            [
                'required' => false,
                'widget'   => 'single_text',
                'label'    => $this->translator->trans('published.label', [], 'gestion.search.form'),
                'help'     => $this->translator->trans('published.help', [], 'gestion.search.form'),
            ]
        );
    }

    protected function addRefCategory(FormBuilderInterface $formBuilder): void
    {
        $formBuilder->add(
            'category',
            SearchableType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('refcategory.label', [], 'gestion.search.form'),
                'help'     => $this->translator->trans('refcategory.help', [], 'gestion.search.form'),
                'multiple' => false,
                'class'    => Category::class,
                'route'    => 'api_search_category',
                'attr'     => [
                    'placeholder' => $this->translator->trans('refcategory.placeholder', [], 'gestion.search.form'),
                ],
            ]
        );
    }

    protected function addRefUser(FormBuilderInterface $formBuilder): void
    {
        $formBuilder->add(
            'user',
            SearchableType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('refuser.label', [], 'gestion.search.form'),
                'help'     => $this->translator->trans('refuser.help', [], 'gestion.search.form'),
                'multiple' => false,
                'class'    => User::class,
                'route'    => 'api_search_user',
                'attr'     => [
                    'placeholder' => $this->translator->trans('refuser.placeholder', [], 'gestion.search.form'),
                ],
            ]
        );
    }

    protected function showState(
        FormBuilderInterface $formBuilder,
        EntityInterface $entity,
        string $label,
        string $help,
        string $placeholder
    ): void
    {
        /** @var WorkflowInterface $workflow */
        $workflow = $this->workflowService->get($entity);
        if (!$workflow instanceof WorkflowInterface) {
            return;
        }

        $definition = $workflow->getDefinition();
        $places     = $definition->getPlaces();
        $formBuilder->add(
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
