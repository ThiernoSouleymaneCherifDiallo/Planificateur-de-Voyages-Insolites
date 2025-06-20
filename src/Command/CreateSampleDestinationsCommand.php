<?php

namespace App\Command;

use App\Entity\Destination;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-sample-destinations',
    description: 'Créer des destinations d\'exemple',
)]
class CreateSampleDestinationsCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $destinations = [
            [
                'name' => 'Paris',
                'description' => 'La ville de l\'amour avec ses monuments emblématiques, ses musées et sa gastronomie raffinée.',
                'country' => 'France',
                'city' => 'Paris',
                'climate' => 'temperate',
                'averageTemperature' => 12.5,
                'budgetLevel' => 2,
                'type' => 'culture',
                'duration' => 5,
                'imageUrl' => 'https://images.unsplash.com/photo-1502602898535-0b7b0b7b0b7b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
            ],
            [
                'name' => 'Tokyo',
                'description' => 'Métropole futuriste où tradition et modernité se rencontrent dans une symphonie urbaine unique.',
                'country' => 'Japon',
                'city' => 'Tokyo',
                'climate' => 'temperate',
                'averageTemperature' => 15.8,
                'budgetLevel' => 3,
                'type' => 'urban',
                'duration' => 7,
                'imageUrl' => 'https://images.unsplash.com/photo-1540959733332-eab4deabeeaf?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
            ],
            [
                'name' => 'Bali',
                'description' => 'Île paradisiaque avec ses temples ancestraux, ses rizières verdoyantes et ses plages de sable blanc.',
                'country' => 'Indonésie',
                'city' => 'Bali',
                'climate' => 'tropical',
                'averageTemperature' => 27.5,
                'budgetLevel' => 1,
                'type' => 'relaxation',
                'duration' => 10,
                'imageUrl' => 'https://images.unsplash.com/photo-1537953773345-d172ccf13cf1?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
            ],
            [
                'name' => 'New York',
                'description' => 'La ville qui ne dort jamais, avec ses gratte-ciels, ses musées et son énergie incomparable.',
                'country' => 'États-Unis',
                'city' => 'New York',
                'climate' => 'continental',
                'averageTemperature' => 13.0,
                'budgetLevel' => 3,
                'type' => 'urban',
                'duration' => 6,
                'imageUrl' => 'https://images.unsplash.com/photo-1496442226666-8d4d0e62e6e9?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
            ],
            [
                'name' => 'Marrakech',
                'description' => 'Cité impériale aux mille couleurs, entre souks animés, palais somptueux et désert mystérieux.',
                'country' => 'Maroc',
                'city' => 'Marrakech',
                'climate' => 'desert',
                'averageTemperature' => 23.5,
                'budgetLevel' => 1,
                'type' => 'culture',
                'duration' => 4,
                'imageUrl' => 'https://images.unsplash.com/photo-1553603228-0f4033aaacd6?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80'
            ]
        ];

        foreach ($destinations as $data) {
            $destination = new Destination();
            $destination->setName($data['name']);
            $destination->setDescription($data['description']);
            $destination->setCountry($data['country']);
            $destination->setCity($data['city']);
            $destination->setClimate($data['climate']);
            $destination->setAverageTemperature($data['averageTemperature']);
            $destination->setBudgetLevel($data['budgetLevel']);
            $destination->setType($data['type']);
            $destination->setDuration($data['duration']);
            $destination->setImageUrl($data['imageUrl']);

            $this->entityManager->persist($destination);
        }

        $this->entityManager->flush();

        $io->success('5 destinations d\'exemple ont été créées avec succès !');
        $io->info('Destinations créées : Paris, Tokyo, Bali, New York, Marrakech');

        return Command::SUCCESS;
    }
} 