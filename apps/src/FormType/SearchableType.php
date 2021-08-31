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

class SearchableType extends AbstractType
{

    protected RouterInterface $router;

    protected EntityManagerInterface $entityManager;

    public function __construct(
        RouterInterface $router,
        EntityManagerInterface $entityManager
    )
    {
        $this->entityManager = $entityManager;
        $this->router        = $router;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('class');
        $resolver->setRequired('route');
        $resolver->setDefaults(
            [
                'compound' => false,
                'multiple' => true,
                'attr'     => ['is' => 'select-selector'],
            ]
        );
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(
            new CallbackTransformer(
                function ($value) {
                    if ($value instanceof Collection) {
                        return $value->map(
                            fn ($d) => (string) $d->getId()
                        )->toArray();
                    }
                },
                function ($ids) use ($options) {
                    if (empty($ids)) {
                        return is_array($ids) ? new ArrayCollection([]) : null;
                    }

                    $repository = $this->entityManager->getRepository($options['class']);

                    return is_array($ids) ? new ArrayCollection(
                        $repository->findBy(['id' => $ids])
                    ) : $repository->find($ids);
                }
            )
        );
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['expanded'] = false;

        $placeholder = isset($options['placeholder']) ? $options['placeholder'] : null;

        $view->vars['placeholder']               = $placeholder;
        $view->vars['placeholder_in_choices']    = false;
        $view->vars['multiple']                  = $options['multiple'];
        $view->vars['preferred_choices']         = [];
        $view->vars['choices']                   = $this->choices($form->getData());
        $view->vars['choice_translation_domain'] = false;
        if ($options['multiple']) {
            $view->vars['full_name'] .= '[]';
        }

        $attr = $options['attr'];

        $attr['data-url'] = $this->router->generate(
            $options['route'],
            isset($options['route_param']) ? $options['route_param'] : []
        );

        $view->vars['attr'] = $attr;
    }

    public function getBlockPrefix()
    {
        return 'choice';
    }

    private function choices($values)
    {
        if ($values instanceof Collection) {
            return $values->map(fn ($d) => new ChoiceView($d, (string) $d->getId(), (string) $d))->toArray();
        }
        
        return [new ChoiceView($values, (string) $values->getId(), (string) $values)];
    }
}
