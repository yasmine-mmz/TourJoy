<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Captcha\Bundle\CaptchaBundle\Validator\Constraints\ValidCaptcha;
use Captcha\Bundle\CaptchaBundle\Form\Type\CaptchaType;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Security\Core\Security;

class ReservationType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();

        $builder
            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
                'html5' => true, 
                'attr' => [
                    'class' => 'form-control', 
                    'placeholder' => 'Select Date', 
                    'autocomplete' => 'off', 
                ],
            ])
            ->add('endDate', DateType::class, [
                'widget' => 'single_text',
                'html5' => true, 
                'attr' => [
                    'class' => 'form-control', 
                    'placeholder' => 'Select Date', 
                    'autocomplete' => 'off', 
                ],
            ])
            ->add('name')
            ->add('captchaCode', CaptchaType::class, [
                'captchaConfig' => 'ExampleCaptcha',
                'constraints' => [
                    new ValidCaptcha([
                        'message' => 'Invalid captcha, please try again',
                    ]),
                ]
            ])
            ->add('BOOK', SubmitType::class);

 if ($user instanceof User) {
    $builder->add('fkuser', EntityType::class, [
        'class' => User::class,
        'choice_label' => false,
        'data' => $user,
        // 'mapped' => false, // Remove this line or set it to true
        'attr' => ['style' => 'display: none;'],
    ]);
}
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
            'user' => null,
        ]);
    }
}