<?php

namespace Labstag\Form\Admin\Search;

use Labstag\Entity\Chapter;
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
                'label'    => $this->translator->trans('chapter.title.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('chapter.title.help', [], 'admin.search.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans('chapter.title.placeholder', [], 'admin.search.form'),
                ],
            ]
        );
        $builder->add(
            'published',
            DateType::class,
            [
                'required' => false,
                'widget'   => 'single_text',
                'label'    => $this->translator->trans('chapter.published.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('chapter.published.help', [], 'admin.search.form'),
            ]
        );
        $this->showState(
            $builder,
            new Chapter(),
            $this->translator->trans('chapter.etape.label', [], 'admin.search.form'),
            $this->translator->trans('chapter.etape.help', [], 'admin.search.form'),
            $this->translator->trans('chapter.etape.placeholder', [], 'admin.search.form')
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

    public function getBlockPrefix(): string
    {
        return '';
    }
}
