<?php

namespace Labstag\Form\Admin\Search;

use Labstag\Entity\Category;
use Labstag\Entity\Post;
use Labstag\Entity\User;
use Labstag\FormType\SearchableType;
use Labstag\Lib\AbstractTypeLib;
use Labstag\Search\PostSearch;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Workflow\Registry;
use Symfony\Contracts\Translation\TranslatorInterface;

class PostType extends AbstractTypeLib
{

    protected Registry $workflows;

    public function __construct(
        Registry $workflows,
        TranslatorInterface $translator
    )
    {
        $this->workflows = $workflows;
        parent::__construct($translator);
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
            'title',
            TextType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('post.title.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('post.title.help', [], 'admin.search.form'),
            ]
        );
        $builder->add(
            'refuser',
            SearchableType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('post.refuser.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('post.refuser.help', [], 'admin.search.form'),
                'multiple' => false,
                'class'    => User::class,
                'route'    => 'api_search_user',
            ]
        );
        $builder->add(
            'refcategory',
            SearchableType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('post.refcategory.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('post.refcategory.help', [], 'admin.search.form'),
                'multiple' => false,
                'class'    => Category::class,
                'route'    => 'api_search_category',
            ]
        );
        $builder->add(
            'published',
            DateType::class,
            [
                'required' => false,
                'widget'   => 'single_text',
                'label'    => $this->translator->trans('post.published.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('post.published.help', [], 'admin.search.form'),
            ]
        );
        $workflow   = $this->workflows->get(new Post());
        $definition = $workflow->getDefinition();
        $places     = $definition->getPlaces();
        $builder->add(
            'etape',
            ChoiceType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('post.etape.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('post.etape.help', [], 'admin.search.form'),
                'choices'  => $places,
            ]
        );
        $builder->add(
            'submit',
            SubmitType::class,
            [
                'attr' => ['name' => ''],
            ]
        );
        $builder->add(
            'reset',
            ResetType::class,
            [
                'attr' => ['name' => ''],
            ]
        );
        unset($options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class'      => PostSearch::class,
                'csrf_protection' => false,
                'method'          => 'GET',
            ]
        );
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
