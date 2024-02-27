<?php

namespace App\Form;

use App\Entity\Guide;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;





class GuideType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('CIN')
            ->add('firstnameG')
            ->add('lastnameG')
            ->add('emailaddressG')
            ->add('phonenumberG')
            ->add('countryG', CountryType::class, [
                'label' => 'Country',
                'preferred_choices' => ['US', 'GB', 'CA'], // Optional: Set preferred choices
                'placeholder' => 'Choose a country', // Optional: Placeholder text
            ])    
            ->add('language', ChoiceType::class, [
                'choices' => [
                    'English' => 'English',
                    'Mandarin' => 'Mandarin',
                    'Hindi' => 'Hindi',
                    'Spanish' => 'Spanish',
                    'French' => 'French',
                    'Arabic' => 'Arabic',
                    'Bengali' => 'Bengali',
                    'Russian' => 'Russian',
                    'Portuguese' => 'Portuguese',
                    'Urdu' => 'Urdu',
                ],])
                        ->add('dob', DateType::class, [
                'widget' => 'single_text',
                'html5' => true, // Set to true to use HTML5 date input
                'attr' => [
                    'class' => 'form-control', // Bootstrap class for styling
                    'placeholder' => 'Select Date', // Placeholder text
                    'autocomplete' => 'off', // Disable autocomplete
                ],
            ])  
               ->add('genderG', ChoiceType::class, [
                'choices' => [
                    'Male' => 'Male',
                    'Female' => 'Female',
                ],
            ])

            ->add('imageFile', VichImageType::class, [
                'label' => 'Image',
                'label_attr' => ['class' => 'form-label mt-4']
            ])
            ->add('save',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Guide::class,
        ]);
    }

  
}
