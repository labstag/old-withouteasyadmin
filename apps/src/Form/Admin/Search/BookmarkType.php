<?php

namespace Labstag\Form\Admin\Search;

use Labstag\Entity\Bookmark;
use Labstag\Entity\Category;
use Labstag\Entity\User;
use Labstag\FormType\SearchableType;
use Labstag\Lib\AbstractTypeLib;
use Labstag\Search\BookmarkSearch;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Workflow\Registry;
use Symfony\Contracts\Translation\TranslatorInterface;

class BookmarkType extends AbstractTypeLib
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
            'name',
            TextType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('bookmark.name.label', [], 'admin.form'),
                'help'     => $this->translator->trans('bookmark.name.help', [], 'admin.form'),
            ]
        );
        $builder->add(
            'refuser',
            SearchableType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('bookmark.refuser.label', [], 'admin.form'),
                'help'     => $this->translator->trans('bookmark.refuser.help', [], 'admin.form'),
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
                'label'    => $this->translator->trans('bookmark.refcategory.label', [], 'admin.form'),
                'help'     => $this->translator->trans('bookmark.refcategory.help', [], 'admin.form'),
                'multiple' => false,
                'class'    => Category::class,
                'route'    => 'api_search_category',
            ]
        );
        $workflow   = $this->workflows->get(new Bookmark());
        $definition = $workflow->getDefinition();
        $places     = $definition->getPlaces();
        $builder->add(
            'etape',
            ChoiceType::class,
            [
                'required' => false,
                'label'    => $this->translator->trans('bookmark.etape.label', [], 'admin.form'),
                'help'     => $this->translator->trans('bookmark.etape.help', [], 'admin.form'),
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
                'data_class'      => BookmarkSearch::class,
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
