<?php

namespace Labstag\Form\Gestion\Search;

use Labstag\Entity\Chapter;
use Labstag\Lib\SearchAbstractTypeLib;
use Labstag\Search\ChapterSearch;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChapterType extends SearchAbstractTypeLib
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
                'label'    => $this->translator->trans('chapter.title.label', [], 'gestion.search.form'),
                'help'     => $this->translator->trans('chapter.title.help', [], 'gestion.search.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans('chapter.title.placeholder', [], 'gestion.search.form'),
                ],
            ]
        );
        $this->addPublished($formBuilder);
        $this->showState(
            $formBuilder,
            new Chapter(),
            $this->translator->trans('chapter.etape.label', [], 'gestion.search.form'),
            $this->translator->trans('chapter.etape.help', [], 'gestion.search.form'),
            $this->translator->trans('chapter.etape.placeholder', [], 'gestion.search.form')
        );
        parent::buildForm($formBuilder, $options);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class'      => ChapterSearch::class,
                'csrf_protection' => false,
                'method'          => 'GET',
            ]
        );
    }
}
