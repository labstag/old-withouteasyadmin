<?php

namespace Labstag\Form\Gestion;

use Labstag\Entity\Edito;
use Labstag\Entity\User;
use Labstag\FormType\SearchableType;
use Labstag\FormType\UploadType;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditoType extends AbstractTypeLib
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
                'label' => $this->translator->trans('edito.title.label', [], 'gestion.form'),
                'help'  => $this->translator->trans('edito.title.help', [], 'gestion.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('edito.title.placeholder', [], 'gestion.form'),
                ],
            ]
        );
        $this->addPublished($formBuilder);
        $this->setContent($formBuilder);
        $formBuilder->add(
            'file',
            UploadType::class,
            [
                'label'    => $this->translator->trans('edito.file.label', [], 'gestion.form'),
                'help'     => $this->translator->trans('edito.file.help', [], 'gestion.form'),
                'required' => false,
                'attr'     => ['accept' => 'image/*'],
            ]
        );
        $formBuilder->add(
            'refuser',
            SearchableType::class,
            [
                'label'    => $this->translator->trans('edito.refuser.label', [], 'gestion.form'),
                'help'     => $this->translator->trans('edito.refuser.help', [], 'gestion.form'),
                'multiple' => false,
                'class'    => User::class,
                'route'    => 'api_search_user',
                'attr'     => [
                    'placeholder' => $this->translator->trans('edito.refuser.placeholder', [], 'gestion.form'),
                ],
            ]
        );
        $this->addParagraph(
            $formBuilder,
            [
                'add'    => 'gestion_edito_paragraph_add',
                'edit'   => 'gestion_edito_paragraph_show',
                'delete' => 'gestion_edito_paragraph_delete',
            ]
        );
        $this->setMeta($formBuilder);
        unset($options);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults(
            [
                'data_class' => Edito::class,
            ]
        );
    }
}
