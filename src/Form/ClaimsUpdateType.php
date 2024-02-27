<?php

namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Claims;
use App\Entity\Categories;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
class ClaimsUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'label' => false,
                'constraints' => [
                    new Length(['max' => 64]),
                ],
                'attr' => [
                    'style' => 'display: none;', // Hide the title field
                ],
            ])
            
            ->add('description', TextType::class, [
                'label' => false,
                
                'attr' => [
                    'style' => 'display: none;', // Hide the description field
                ],
            ])
            ->add('createDate', null, [
                'label' => false,
                'attr' => [
                    'style' => 'display: none;', // Hide the createDate field
                ],
            ])
            ->add('state', ChoiceType::class, [
                'choices' => [
                    'Treated' => 'treated',
                    'Not treated' => 'not_treated',
                ],
                'placeholder' => 'Choose the state',
                'constraints' => [
                    new Choice([
                        'choices' => ['treated', 'not_treated'],
                        'strict' => true,
                    ]),
                ],
            ])
            ->add('reply')
            ->add('fkC', EntityType::class, [
                'label' => false,
                'class' => Categories::class,
                'choice_label' => 'name',
                'attr' => [
                    'style' => 'display: none;', // Hide the description field
                ],
                'placeholder' => 'Select a category',
            ])
            
        
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Claims::class,
        ]);
    }
}