<?php

namespace App\Form;

use App\Entity\Accomodation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Vich\UploaderBundle\Form\Type\VichImageType; 
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AccomodationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('fkpays')
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Private house' => 'private house',
                    'Guesthouse' => 'guesthouse',
                    'Apartment' => 'apartment',
                    'Villa' => 'villa',
                ],
                'attr' => [
                    'class' => 'form-control custom-select'
                ],
            ])
            ->add('nbRooms')
            ->add('price')
            ->add('location', TextType::class, [
                'label' => 'Address'
            ])
            ->add('imageFile', VichImageType::class, [
                'label' => 'accommodation picture'
            ])
            ->add('save',SubmitType::class)        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Accomodation::class,
        ]);
    }
}
