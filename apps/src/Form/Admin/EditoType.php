<?php

namespace Labstag\Form\Admin;

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
                'label' => $this->translator->trans('edito.title.label', [], 'admin.form'),
                'help'  => $this->translator->trans('edito.title.help', [], 'admin.form'),
                'attr'  => [
                    'placeholder' => $this->translator->trans('edito.title.placeholder', [], 'admin.form'),
                ],
            ]
        );
        $this->addPublished($formBuilder);
        $this->setContent($formBuilder);
        $formBuilder->add(
            'file',
            UploadType::class,
            [
                'label'    => $this->translator->trans('edito.file.label', [], 'admin.form'),
                'help'     => $this->translator->trans('edito.file.help', [], 'admin.form'),
                'required' => false,
                'attr'     => ['accept' => 'image/*'],
            ]
        );
        $formBuilder->add(
            'refuser',
            SearchableType::class,
            [
                'label'    => $this->translator->trans('edito.refuser.label', [], 'admin.form'),
                'help'     => $this->translator->trans('edito.refuser.help', [], 'admin.form'),
                'multiple' => false,
                'class'    => User::class,
                'route'    => 'api_search_user',
                'attr'     => [
                    'placeholder' => $this->translator->trans('edito.refuser.placeholder', [], 'admin.form'),
                ],
            ]
        );
        $this->addParagraph(
            $formBuilder,
            [
                'add'    => 'admin_edito_paragraph_add',
                'edit'   => 'admin_edito_paragraph_show',
                'delete' => 'admin_edito_paragraph_delete',
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
