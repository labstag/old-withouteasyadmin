<?php

namespace Labstag\Form\Admin\Bookmark;

use Labstag\FormType\UploadType;
use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImportType extends AbstractTypeLib
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
            'file',
            UploadType::class,
            [
                'label'    => $this->translator->trans('bookmark.import.label', [], 'admin.form'),
                'help'     => $this->translator->trans('bookmark.import.help', [], 'admin.form'),
                'required' => false,
                'attr'     => ['accept' => 'text/html'],
            ]
        );
        unset($options);
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        $optionsResolver->setDefaults([]);
    }
}
