<?php

namespace Labstag\Form\Front;

use Labstag\Lib\AbstractTypeLib;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractTypeLib
{
    public function buildForm(
        FormBuilderInterface $formBuilder,
        array $options
    ): void {
        $formBuilder->add(
            'name',
            TextType::class,
            [
                'label'           => 'Nom *',
                'invalid_message' => 'Veuillez remplir ce champ',
                'attr'            => ['placeholder' => 'Exemple : Pierre-Louis'],
            ]
        );
        $formBuilder->add(
            'firstname',
            TextType::class,
            [
                'label'           => 'Prénom *',
                'invalid_message' => 'Veuillez remplir ce champ',
                'attr'            => ['placeholder' => 'Exemple: Piere-Louis'],
            ]
        );
        $formBuilder->add(
            'mail',
            EmailType::class,
            [
                'label'           => 'Email *',
                'invalid_message' => 'Veuillez remplir ce champ',
                'attr'            => ['placeholder' => 'Exemple : nomprenom@domaine.fr'],
            ]
        );
        $formBuilder->add(
            'phone',
            TextType::class,
            [
                'label'           => 'Téléphone *',
                'invalid_message' => 'Veuillez remplir ce champ',
                'attr'            => ['placeholder' => 'Exemple : 06 05 04 03 02'],
            ]
        );

        $placeholder = "Exemple : Bonjour, je souhaite vous contacter pour plus d'informations";
        $formBuilder->add(
            'message',
            TextareaType::class,
            [
                'label'           => 'Message *',
                'invalid_message' => 'Veuillez remplir ce champ',
                'attr'            => ['placeholder' => $placeholder],
            ]
        );
        $formBuilder->add(
            'optin',
            CheckboxType::class,
            [
                'label' => $options['option_label'],
            ]
        );
        $formBuilder->add(
            'submit',
            SubmitType::class,
            ['label' => 'Envoyer']
        );
    }

    public function configureOptions(OptionsResolver $optionsResolver): void
    {
        // Configure your form options here
        $optionsResolver->setDefaults(
            [
                'csrf_field_name' => '_csrf_token',
                'csrf_token_id'   => 'authenticate',
            ]
        );
    }

    public function getBlockPrefix(): string
    {
        return 'contact';
    }
}
