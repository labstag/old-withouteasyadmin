<?php

namespace Labstag\FormType;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class SearchableType extends AbstractType
{
    public function __construct(
        protected RouterInterface $router,
        protected TranslatorInterface $translator,
        protected EntityManagerInterface $entityManager
    )
    {
    }

    public function buildForm(FormBuilderInterface $formBuilder, array $options): void
    {
        $formBuilder->addModelTransformer(
            new CallbackTransformer(
                static function ($value) {
                    if ($value instanceof Collection) {
                        return $value->map(
                            static fn($d) => (string) $d->getId()
                        )->toArray();
                    }
                },
                function ($ids) use ($options) {
                    if (empty($ids)) {
                        return is_array($ids) ? new ArrayCollection([]) : null;
                    }

                    $entityRepository = $this->entityManager->getRepository($options['class']);
                    if ($options['add'] && is_array($ids)) {
                        $ids = $this->addToentity($ids, $options);
                    }

                    return is_array($ids) ? new ArrayCollection(
                        $entityRepository->findBy(['id' => $ids])
                    ) : $entityRepository->find($ids);
                }
            )
        );
    }

    public function buildView(FormView $formView, FormInterface $form, array $options): void
    {
        $formView->vars['expanded'] = false;

        $placeholder = $options['placeholder'] ?? null;

        $formView->vars['placeholder']               = $placeholder;
        $formView->vars['placeholder_in_choices']    = false;
        $formView->vars['multiple']                  = $options['multiple'];
        $formView->vars['preferred_choices']         = [];
        $formView->vars['choices']                   = $this->choices($form->getData(), $options);
        $formView->vars['choice_translation_domain'] = false;
        if ($options['multiple']) {
            $formView->vars['full_name'] .= '[]';
        }

        $attr = $options['attr'];

        $attr['data-url'] = $this->router->generate(
            $options['route'],
            $options['route_param'] ?? []
        );

        $attr['data-add'] = $options['add'] ? 1 : 0;
        if ($options['add']) {
            $attr['data-addmessage'] = $this->translator->trans('select.add');
        }

        $formView->vars['attr'] = $attr;
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setRequired('class');
        $optionsResolver->setRequired('route');
        $optionsResolver->setDefaults(
            [
                'new'      => null,
                'add'      => false,
                'compound' => false,
                'multiple' => true,
                'attr'     => [
                    'data-noresult' => $this->translator->trans('select.noresult'),
                    'is'            => 'select-selector',
                ],
            ]
        );
    }

    public function getBlockPrefix(): string
    {
        return 'choice';
    }

    /**
     * @return mixed[]
     */
    protected function addToentity(array $ids, $options): array
    {
        if (is_null($options['new'])) {
            return $ids;
        }

        $entityManager = $this->entityManager;
        $entityRepository    = $entityManager->getRepository($options['class']);
        foreach ($ids as $id => $key) {
            $entity = $entityRepository->find($key);
            if ($entity instanceof $options['class']) {
                continue;
            }

            $entity = clone $options['new'];
            $entity->setString($key);
            $entityRepository->add($entity);
            $ids[$id] = $entity->getId();
        }

        return $ids;
    }

    private function choices($values, array $options): ?array
    {
        if (is_null($values)) {
            return ($options['multiple']) ? [] : null;
        }

        if ($values instanceof Collection) {
            return $values->map(static fn($d) => new ChoiceView($d, (string) $d->getId(), (string) $d))->toArray();
        }

        return [new ChoiceView($values, (string) $values->getId(), (string) $values)];
    }
}
