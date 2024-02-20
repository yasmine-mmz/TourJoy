<?php

namespace App\Form;

use App\Entity\Booking;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;


class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
       ->add('date', DateType::class, [
            'widget' => 'single_text',
            'html5' => true, // Set to true to use HTML5 date input
            'attr' => [
                'class' => 'form-control', // Bootstrap class for styling
                'placeholder' => 'Select Date', // Placeholder text
                'autocomplete' => 'off', // Disable autocomplete
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
