<?php
namespace App\Form;

use App\Entity\Country;
use App\Entity\Monument;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MonumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nameM', null, [
            'label' => 'Monument Name',
            'attr' => ['class' => 'single-input', 'placeholder' => 'Monument Name'],
        ])
        ->add('type', ChoiceType::class, [
            'choices' => [
                'War Memorials' => 'War Memorials',
                'Historical Monuments' => 'Historical Monuments',
                'Architectural Monuments' => 'Architectural Monuments',
                'Cultural Monuments' => 'Cultural Monuments',
                'National Monuments' => 'National Monuments',
                'Artistic Monuments' => 'Artistic Monuments',
                'Natural Monuments' => 'Natural Monuments',
                'Memorials' => 'Memorials',
                'Functional Monuments' => 'Functional Monuments',
                'Symbolic Monuments' => 'Symbolic Monuments',
                'Heritage Monuments' => 'Heritage Monuments',
                'Public Monuments' => 'Public Monuments',
            ],
          
        ])
        ->add('entryprice')
        ->add('status', ChoiceType::class, [
            'choices' => [
                'Active' => 'Active',
                'Not Active' => 'Not Active',
            ],
        ])
        ->add('description', TextareaType::class, [
            'label' => 'Description',
            'attr' => ['class' => 'form-control', 'placeholder' => 'Description'],
        ])
                ->add('latitude', null, [
            'label' => 'Latitude',
            'attr' => ['class' => 'form-control', 'placeholder' => 'Latitude'],
        ])
        ->add('longitude', null, [
            'label' => 'Longitude',
            'attr' => ['class' => 'form-control', 'placeholder' => 'Longitude'],
        ])
        ->add('imageFile', VichImageType::class, [
            'label' => 'Image',
            'label_attr' => ['class' => 'form-label mt-4']
        ])
        ->add('fkcountry', EntityType::class, [
            'class' => Country::class,
            'choice_label' => 'name',
            'label' => 'Country:',
        ])
        ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Monument::class,
        ]);
    }
}
