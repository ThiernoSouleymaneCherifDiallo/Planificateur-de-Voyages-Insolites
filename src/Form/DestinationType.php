<?php

namespace App\Form;

use App\Entity\Destination;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DestinationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la destination',
                'attr' => ['placeholder' => 'Ex: Paris, Tokyo, New York...']
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => ['rows' => 5, 'placeholder' => 'Décrivez cette destination...']
            ])
            ->add('country', TextType::class, [
                'label' => 'Pays',
                'attr' => ['placeholder' => 'Ex: France, Japon, États-Unis...']
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'attr' => ['placeholder' => 'Ex: Paris, Tokyo, New York...']
            ])
            ->add('climate', ChoiceType::class, [
                'label' => 'Climat',
                'choices' => [
                    'Tropical' => 'tropical',
                    'Méditerranéen' => 'mediterranean',
                    'Tempéré' => 'temperate',
                    'Continental' => 'continental',
                    'Polaire' => 'polar',
                    'Désertique' => 'desert',
                    'Montagneux' => 'mountain'
                ]
            ])
            ->add('averageTemperature', NumberType::class, [
                'label' => 'Température moyenne (°C)',
                'scale' => 1,
                'attr' => ['placeholder' => 'Ex: 15.5']
            ])
            ->add('budgetLevel', ChoiceType::class, [
                'label' => 'Niveau de budget',
                'choices' => [
                    'Économique' => 1,
                    'Moyen' => 2,
                    'Luxe' => 3
                ]
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type de voyage',
                'choices' => [
                    'Aventure' => 'adventure',
                    'Culture' => 'culture',
                    'Détente' => 'relaxation',
                    'Gastronomie' => 'gastronomy',
                    'Nature' => 'nature',
                    'Sport' => 'sport',
                    'Urbain' => 'urban',
                    'Rural' => 'rural'
                ]
            ])
            ->add('duration', IntegerType::class, [
                'label' => 'Durée recommandée (jours)',
                'attr' => ['placeholder' => 'Ex: 7']
            ])
            ->add('imageUrl', UrlType::class, [
                'label' => 'URL de l\'image',
                'required' => false,
                'attr' => ['placeholder' => 'https://example.com/image.jpg']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Destination::class,
        ]);
    }
} 