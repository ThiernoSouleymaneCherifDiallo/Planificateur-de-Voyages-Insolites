<?php

namespace App\Form;

use App\Entity\Preference;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PreferenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('climate', ChoiceType::class, [
                'label' => 'Climat préféré',
                'choices' => [
                    'Tropical' => 'tropical',
                    'Méditerranéen' => 'mediterranean',
                    'Tempéré' => 'temperate',
                    'Continental' => 'continental',
                    'Polaire' => 'polar',
                    'Désertique' => 'desert',
                    'Montagneux' => 'mountain'
                ],
                'placeholder' => 'Choisissez un climat'
            ])
            ->add('minBudget', IntegerType::class, [
                'label' => 'Budget minimum (€)',
                'attr' => ['min' => 0, 'placeholder' => 'Ex: 500']
            ])
            ->add('maxBudget', IntegerType::class, [
                'label' => 'Budget maximum (€)',
                'attr' => ['min' => 0, 'placeholder' => 'Ex: 3000']
            ])
            ->add('minDuration', IntegerType::class, [
                'label' => 'Durée minimum (jours)',
                'attr' => ['min' => 1, 'placeholder' => 'Ex: 3']
            ])
            ->add('maxDuration', IntegerType::class, [
                'label' => 'Durée maximum (jours)',
                'attr' => ['min' => 1, 'placeholder' => 'Ex: 15']
            ])
            ->add('preferredCountry', TextType::class, [
                'label' => 'Pays préféré (optionnel)',
                'required' => false,
                'attr' => ['placeholder' => 'Ex: France, Japon...']
            ])
            ->add('adventure', CheckboxType::class, [
                'label' => 'Aventure',
                'required' => false
            ])
            ->add('culture', CheckboxType::class, [
                'label' => 'Culture',
                'required' => false
            ])
            ->add('relaxation', CheckboxType::class, [
                'label' => 'Détente',
                'required' => false
            ])
            ->add('gastronomy', CheckboxType::class, [
                'label' => 'Gastronomie',
                'required' => false
            ])
            ->add('nature', CheckboxType::class, [
                'label' => 'Nature',
                'required' => false
            ])
            ->add('sport', CheckboxType::class, [
                'label' => 'Sport',
                'required' => false
            ])
            ->add('urban', CheckboxType::class, [
                'label' => 'Urbain',
                'required' => false
            ])
            ->add('rural', CheckboxType::class, [
                'label' => 'Rural',
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Preference::class,
        ]);
    }
} 