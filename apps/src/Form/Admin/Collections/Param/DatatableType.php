<?php

namespace Labstag\Form\Admin\Collections\Param;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DatatableType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void
    {
        $lang = $this->getFilesLang();
        $builder->add(
            'lang',
            ChoiceType::class,
            ['choices' => $lang]
        );
        $builder->add('pagelist', TextType::class);
        unset($options);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        // Configure your form options here
        $resolver->setDefaults(
            []
        );
    }

    private function getFilesLang(): array
    {
        $tabLang = [];
        /** @var array $files */
        $files = glob('../node_modules/bootstrap-table/dist/locale/*');
        foreach ($files as $file) {
            $code = 'bootstrap-table-';
            /** @var string $lang */
            $lang = substr($file, (strpos($file, $code) + strlen($code)));
            /** @var string $lang */
            $lang           = substr($lang, 0, (int) strpos($lang, '.'));
            $tabLang[$lang] = $lang;
        }

        return $tabLang;
    }
}
