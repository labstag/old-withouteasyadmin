<?php

namespace Labstag\Form\Admin\Search;

use Labstag\Entity\Edito;
use Labstag\Entity\User;
use Labstag\FormType\SearchableType;
use Labstag\Lib\SearchAbstractTypeLib;
use Labstag\Search\EditoSearch;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditoType extends SearchAbstractTypeLib
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
                'label'    => $this->translator->trans('edito.title.label', [], 'admin.search.form'),
                'help'     => $this->translator->trans('edito.title.help', [], 'admin.search.form'),
                'attr'     => [
                    'placeholder' => $this->translator->trans('edito.title.placeholder', [], 'admin.search.form'),
                ],
            ]
        );
        $this->addRefUser($builder);
        $this->addPublished($builder);
        $this->showState(
            $builder,
            new Edito(),
            $this->translator->trans('edito.etape.label', [], 'admin.search.form'),
            $this->translator->trans('edito.etape.help', [], 'admin.search.form'),
            $this->translator->trans('edito.etape.placeholder', [], 'admin.search.form')
        );
        parent::buildForm($builder, $options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class'      => EditoSearch::class,
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
