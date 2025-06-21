<?php

namespace App\Form;

use App\Entity\Destination;
use App\Entity\Planification;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlanificationTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('destination', EntityType::class, [
                'class' => Destination::class,
                'choice_label' => 'name',
                'placeholder' => 'Choisissez une destination',
                'label' => 'Destination',
                'attr' => [
                    'class' => 'form-select',
                ],
            ])
            ->add('dateDepart', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de départ',
                'attr' => [
                    'class' => 'form-control',
                    'min' => date('Y-m-d'),
                ],
            ])
            ->add('dateRetour', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de retour',
                'attr' => [
                    'class' => 'form-control',
                    'min' => date('Y-m-d'),
                ],
            ])
            ->add('budgetEstime', MoneyType::class, [
                'currency' => 'EUR',
                'required' => false,
                'label' => 'Budget estimé (€)',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ex: 1500',
                ],
            ])
            ->add('accompagnants', TextType::class, [
                'required' => false,
                'label' => 'Accompagnants',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ex: Famille, Amis, Seul...',
                ],
            ])
            ->add('hebergement', TextType::class, [
                'required' => false,
                'label' => 'Type d\'hébergement',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ex: Hôtel, Airbnb, Camping...',
                ],
            ])
            ->add('transport', TextType::class, [
                'required' => false,
                'label' => 'Moyen de transport',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ex: Avion, Train, Voiture...',
                ],
            ])
            ->add('activites', TextareaType::class, [
                'required' => false,
                'label' => 'Activités prévues',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 3,
                    'placeholder' => 'Décrivez les activités que vous souhaitez faire...',
                ],
            ])
            ->add('notes', TextareaType::class, [
                'required' => false,
                'label' => 'Notes personnelles',
                'attr' => [
                    'class' => 'form-control',
                    'rows' => 3,
                    'placeholder' => 'Ajoutez vos notes personnelles...',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Planification::class,
        ]);
    }
}
