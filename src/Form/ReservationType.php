<?php

namespace App\Form;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType; // Added DateType
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Captcha\Bundle\CaptchaBundle\Validator\Constraints\ValidCaptcha;
use Captcha\Bundle\CaptchaBundle\Form\Type\CaptchaType;



class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
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
                'captchaConfig' => 'ExampleCaptcha'
            ])
            ->add('BOOK', SubmitType::class);
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
