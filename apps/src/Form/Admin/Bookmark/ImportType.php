<?php

namespace Labstag\Form\Admin\Bookmark;

use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImportType extends AbstractTypeLib
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
            'file',
            FileType::class,
            [
                'label'    => $this->translator->trans('bookmark.import.label', [], 'admin.form'),
                'help'     => $this->translator->trans('bookmark.import.help', [], 'admin.form'),
                'required' => false,
                'attr'     => ['accept' => 'text/html'],
            ]
        );
        unset($options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
