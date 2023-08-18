<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' =>  'quel nom souhaitez-vous donner a votre adresse',
                'attr' => [
                    "placeholder" => 'Nommez votre adresse'
                ]
            ])
            ->add('firstname',TextType::class, [
                'label' =>  'Votre prenom',
                'attr' => [
                    "placeholder" => 'Entrer votre prenom'
                ]
            ])
            ->add('lastname',TextType::class, [
                'label' =>  'Votre nom',
                'attr' => [
                    "placeholder" => 'Entrer votre nom'
                ]
            ])
            ->add('company', TextType::class, [
                'label' =>  'votre société',
                'required' => false,
                'attr' => [
                    "placeholder" => '(facultatif) Entrer le nom de votre société '
                ]
            ])
            ->add('address', TextType::class, [
                'label' =>  'votre adresse',
                'attr' => [
                    "placeholder" => '214 avenue de la seine...'
                ]
            ])
            ->add('postal', TextType::class, [
                'label' =>  'quel est votre code pastal',
                'attr' => [
                    "placeholder" => 'Entrer votre code pastal'
                ]
            ])
            ->add('city', TextType::class, [
                'label' =>  ' Ville',
                'attr' => [
                    "placeholder" => 'Enter votre ville'
                ]
            ])
            ->add('country', CountryType::class, [
                'label' =>  'pays',
                'attr' => [
                    "placeholder" => 'votre pays'
                ]
            ])
            ->add('phone', TelType::class, [
                'label' =>  'Votre telephone',
                'attr' => [
                    "placeholder" => 'Renseigner votre numero de telephone'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider mon addresse',
                'attr' => [
                    'class' =>'btn-block btn-info '
                ]

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
