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
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;



class ClaimsAddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('title', null, [
            'constraints' => [
                new NotBlank(['message' => 'Le titre ne peut pas être vide']),
                new Type(['type' => 'string', 'message' => 'Le titre doit être une chaîne de caractères']),
                new Length(['max' => 255, 'maxMessage' => 'Le titre ne peut pas dépasser {{ limit }} caractères']),
            ],
        ])
        ->add('description', null, [
            'constraints' => [
                new NotBlank(['message' => 'La description ne peut pas être vide']),
                new Type(['type' => 'string', 'message' => 'La description doit être une chaîne de caractères']),
                new Length(['max' => 255, 'maxMessage' => 'La description ne peut pas dépasser {{ limit }} caractères']),
            ],
        ])
            ->add('createDate', DateTimeType::class, [
                'required' => false,
                'disabled' => true,
                'attr' => [
                    'style' => 'display: none;', // Hide the createDate field
                ],
            ])
            ->add('state', TextType::class, [
                'required' => false,
                
                'attr' => [
                    'style' => 'display: none;', // Hide the createDate field
                ],
                'data' => 'Not Treated',
            ])
            ->add('fkC', EntityType::class, [
                'class' => Categories::class,
                'choice_label' => 'name',
                'placeholder' => 'Select a category',
            ])
            ->add('reply', TextType::class, [
                'required' => false,
                
                'attr' => [
                    'style' => 'display: none;', // Hide the createDate field
                ],
                'data' => '-',
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
