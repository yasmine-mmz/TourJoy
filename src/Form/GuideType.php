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
