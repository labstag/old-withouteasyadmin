<?php

namespace Labstag\Form\Gestion\Search;

use Labstag\Entity\Post;
use Labstag\Lib\SearchAbstractTypeLib;
use Labstag\Search\PostSearch;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends SearchAbstractTypeLib
{
    /**
     * @inheritDoc
     */
    public function buildForm(
        FormBuilderInterface $formBuilder,
        array $options
    ): void
    {
        $formBuilder->add(
            'title',
            TextType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('post.title.label', [], 'gestion.search.form'),
                'help'     => $this->translator->trans('post.title.help', [], 'gestion.search.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans('post.title.placeholder', [], 'gestion.search.form'),
                ],
            ]
        );
        $this->addRefUser($formBuilder);
        $this->addRefCategory($formBuilder);
        $this->addPublished($formBuilder);
        $this->showState(
            $formBuilder,
            new Post(),
            $this->translator->trans('post.etape.label', [], 'gestion.search.form'),
            $this->translator->trans('post.etape.help', [], 'gestion.search.form'),
            $this->translator->trans('post.etape.placeholder', [], 'gestion.search.form')
        );
        parent::buildForm($formBuilder, $options);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class'      => PostSearch::class,
                'csrf_protection' => false,
                'method'          => 'GET',
            ]
        );
    }
}
