<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Choice;
use App\Entity\Claims;
use App\Entity\Categories;
use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;




class ClaimsAddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];

        $builder
        ->add('title')
        ->add('description')
            ->add('createDate', DateTimeType::class, [
                'label' => false,
                'required' => false,
                'disabled' => true,
                'attr' => [
                    'style' => 'display: none;',
                ],
            ])
            ->add('state', TextType::class, [
                'label' => false,
                'required' => false,
                
                'attr' => [
                    'style' => 'display: none;', // Hide the createDate field
                ],
                'data' => 'Not Treated',
            ])
            ->add('fkC', EntityType::class, [
                'label' => "Category",
                'class' => Categories::class,
                'choice_label' => 'name',
                'placeholder' => 'Select a category',
                
            ])
            ->add('reply', TextType::class, [
                'label' => false,
                'required' => false,
                
                'attr' => [
                    'style' => 'display: none;', // Hide the createDate field
                ],
                'data' => '-',
            ]);
            if ($user instanceof User) {
                // Set the idU value for the logged-in user
                $builder->add('fkU', EntityType::class, [
                    'class' => User::class,
                    'choice_label' => false,
                    'data' => $user,
                    'mapped' => false,
                    'attr' => ['style' => 'display: none;'],
                ]);
            }
        
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Claims::class,
            'user' => null,
            
        ]);
    }
}
