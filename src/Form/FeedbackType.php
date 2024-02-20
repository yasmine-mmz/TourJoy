<?php

namespace App\Form;

use App\Entity\Feedback;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Guide; // Make sure to import the Guide entity
use Symfony\Bridge\Doctrine\Form\Type\EntityType; // Import EntityType



class FeedbackType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('fkGuide', EntityType::class, [
            'class' => Guide::class,
            'choice_label' => function ($guide) {
                return $guide->getFirstnameG() . ' ' . $guide->getLastnameG();
            },
            // You can customize other options of EntityType as needed
        ])
        
            ->add('rating')
            ->add('comment')
            
        ;
    }   

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Feedback::class,
        ]);
    }
}
