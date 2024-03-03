<?php

namespace App\Form;
use App\Entity\Guide;
use App\Entity\Transport;
use App\Entity\Accomodation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Doctrine\ORM\EntityManagerInterface;


class PreferenceType extends AbstractType
{
    private $entityManager;

public function __construct(EntityManagerInterface $entityManager)
{
    $this->entityManager = $entityManager;
}

public function buildForm(FormBuilderInterface $builder, array $options): void
{
    // Get the unique languages from the Guide entity
    $guideLanguages = $this->entityManager->getRepository(Guide::class)->createQueryBuilder('g')
        ->select('DISTINCT g.language')
        ->getQuery()
        ->getResult();

    // Convert the result to a simple array for the form choices
    $languageChoices = [];
    foreach ($guideLanguages as $language) {
        $languageChoices[$language['language']] = $language['language'];
    }

    // Get the unique transport types from the Transport entity
    $transportTypes = $this->entityManager->getRepository(Transport::class)->createQueryBuilder('t')
        ->select('DISTINCT t.typeT')
        ->getQuery()
        ->getResult();

    // Convert the result to a simple array for the form choices
    $transportTypeChoices = [];
    foreach ($transportTypes as $type) {
        $transportTypeChoices[$type['typeT']] = $type['typeT'];
    }

    // Get the unique accommodation types from the Accomodation entity
    $accommodationTypes = $this->entityManager->getRepository(Accomodation::class)->createQueryBuilder('a')
        ->select('DISTINCT a.type')
        ->getQuery()
        ->getResult();

    // Convert the result to a simple array for the form choices
    $accommodationTypeChoices = [];
    foreach ($accommodationTypes as $type) {
        $accommodationTypeChoices[$type['type']] = $type['type'];
    }

    // Build the form with the updated fields
    $builder
        ->add('region', ChoiceType::class, [
            'choices' => [
                'Africa' => 'Africa',
                'Antarctica' => 'Antarctica',
                'Asia' => 'Asia',
                'Europe' => 'Europe',
                'North America' => 'North America',
                'Oceania' => 'Oceania',
                'South America' => 'South America',
            ],
            'placeholder' => 'Choose a region',
            'label' => 'Region',
        ])
        ->add('visaRequired', CheckboxType::class, [
            'label'    => 'Visa required?',
            'required' => false,
        ])
        ->add('language', ChoiceType::class, [
            'choices' => $languageChoices,
            'label' => 'Preferred Guide Language',
            'placeholder' => 'Select a Language',
            'required' => false,
        ])
        ->add('tripDuration', IntegerType::class, [
            'label' => 'How many days is your trip?',
            'required' => false,
        ])
        ->add('transportType', ChoiceType::class, [
            'choices' => $transportTypeChoices,
        ])
        ->add('accommodationType', ChoiceType::class, [
            'choices' => $accommodationTypeChoices,
        ]);
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
