<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType; // Importation ajoutée
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length; // Importation ajoutée
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'disabled' => true,
                'label' => 'Mon adresse email'
            ])
            ->add('firstname', TextType::class, [
                'disabled' => true,
                'label' => 'Mon prénom'
            ])
            ->add('lastname', TextType::class, [
                'disabled' => true,
                'label' => 'Mon nom'
            ])
            ->add('old_password', PasswordType::class, [
                'label' => 'Ancien mot de passe',
                'mapped' => false, // Assurez-vous de définir 'mapped' sur false
                'attr' => [
                    'placeholder' => 'Veuillez renseigner votre ancien mot de passe'
                ]
            ])

        
            ->add('new_password', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'invalid_message' => 'Le mot de passe et la confirmation doivent être identiques',
                'label' => 'Nouveau mot de passe',
                'constraints' => new Length([
                    'min' => 2,
                    'max' => 30,
                ]),
                'required' => true,
                'first_options' => [
                    'label' => 'Nouveau mot de passe',
                    'attr' => [
                        'placeholder' => 'Veuillez renseigner votre nouveau mot de passe'
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmez votre nouveau mot de passe',
                    'attr' => [
                        'placeholder' => 'Veuillez confirmer votre nouveau mot de passe'
                    ]
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => "mettre a jour"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
