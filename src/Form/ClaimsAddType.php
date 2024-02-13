<?php

namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Claims;
use App\Entity\Categories;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;

class ClaimsAddType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', null, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le titre ne peut pas être vide']),
                    new Assert\Length([
                        'max' => 255,
                        'maxMessage' => 'Le titre ne peut pas dépasser {{ limit }} caractères',
                    ]),
                    new Assert\Type([
                        'type' => 'string',
                        'message' => 'Le titre doit être une chaîne de caractères',
                    ]),
                ],
            ])
            ->add('description', null, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'La description ne peut pas être vide']),
                    new Assert\Length([
                        'max' => 255,
                        'maxMessage' => 'La description ne peut pas dépasser {{ limit }} caractères',
                    ]),
                    new Assert\Type([
                        'type' => 'string',
                        'message' => 'La description doit être une chaîne de caractères',
                    ]),
                ],
            ])
            ->add('createDate')
            ->add('state', null, [
                'constraints' => [
                    new Assert\NotBlank(['message' => "L'état ne peut pas être vide"]),
                    new Assert\Type([
                        'type' => 'integer',
                        'message' => "L'état doit être de type {{ type }}",
                    ]),
                    new Assert\GreaterThanOrEqual([
                        'value' => 0,
                        'message' => "L'état doit être un entier positif ou nul",
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Your password should be at least {{ limit }} characters long.',
                    ]),
                ],
            ])
            ->add('fkC', EntityType::class, [
                'class' => Categories::class,
                'choice_label' => 'name',
                'placeholder' => 'Sélectionnez une catégorie',
                'constraints' => [
                    new Assert\NotBlank(['message' => "La catégorie ne peut pas être vide"]),
                ],
            ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Claims::class,
        ]);
    }
}