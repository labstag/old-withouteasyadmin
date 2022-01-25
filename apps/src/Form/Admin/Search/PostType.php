<?php

namespace Labstag\Form\Admin\Search;

use Labstag\Entity\Category;
use Labstag\Entity\Post;
use Labstag\Entity\User;
use Labstag\FormType\SearchableType;
use Labstag\Lib\SearchAbstractTypeLib;
use Labstag\Search\PostSearch;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends SearchAbstractTypeLib
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
                'label'    => $this->translator->trans('post.title.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('post.title.help', [], 'admin.search.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans('post.title.placeholder', [], 'admin.search.form'),
                ],
            ]
        );
        $this->addRefUser($builder);
        $this->addRefCategory($builder);
        $this->addPublished($builder);
        $this->showState(
            $builder,
            new Post(),
            $this->translator->trans('post.etape.label', [], 'admin.search.form'),
            $this->translator->trans('post.etape.help', [], 'admin.search.form'),
            $this->translator->trans('post.etape.placeholder', [], 'admin.search.form')
        );
        parent::buildForm($builder, $options);
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

    public function getBlockPrefix(): string
    {
        return '';
    }
}
