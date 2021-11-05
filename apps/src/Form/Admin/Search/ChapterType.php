<?php

namespace Labstag\Form\Admin\Search;

use Labstag\Entity\Category;
use Labstag\Entity\Chapter;
use Labstag\Entity\User;
use Labstag\FormType\SearchableType;
use Labstag\Lib\SearchAbstractTypeLib;
use Labstag\Search\ChapterSearch;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChapterType extends SearchAbstractTypeLib
{
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
                'label'    => $this->translator->trans('Chapter.title.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('Chapter.title.help', [], 'admin.search.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans('Chapter.title.placeholder', [], 'admin.search.form'),
                ],
            ]
        );
        $builder->add(
            'refuser',
            SearchableType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('Chapter.refuser.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('Chapter.refuser.help', [], 'admin.search.form'),
                'multiple' => false,
                'class'    => User::class,
                'route'    => 'api_search_user',
                'attr'     => [
                    'placeholder' => $this->translator->trans('Chapter.refuser.placeholder', [], 'admin.search.form'),
                ],
            ]
        );
        $builder->add(
            'refcategory',
            SearchableType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('Chapter.refcategory.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('Chapter.refcategory.help', [], 'admin.search.form'),
                'multiple' => false,
                'class'    => Category::class,
                'route'    => 'api_search_category',
                'attr'     => [
                    'placeholder' => $this->translator->trans('Chapter.refcategory.placeholder', [], 'admin.search.form'),
                ],
            ]
        );
        $builder->add(
            'published',
            DateType::class,
            [
                'required' => false,
                'widget'   => 'single_text',
                'label'    => $this->translator->trans('Chapter.published.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('Chapter.published.help', [], 'admin.search.form'),
            ]
        );
        $workflow   = $this->workflows->get(new Chapter());
        $definition = $workflow->getDefinition();
        $places     = $definition->getPlaces();
        $builder->add(
            'etape',
            ChoiceType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('Chapter.etape.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('Chapter.etape.help', [], 'admin.search.form'),
                'choices'  => $places,
                'attr'     => [
                    'placeholder' => $this->translator->trans('Chapter.etape.placeholder', [], 'admin.search.form'),
                ],
            ]
        );
        parent::buildForm($builder, $options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class'      => ChapterSearch::class,
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
