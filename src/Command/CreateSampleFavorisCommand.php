<?php

namespace App\Command;

use App\Entity\Favori;
use App\Repository\DestinationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-sample-favoris',
    description: 'Crée des favoris d\'exemple pour les utilisateurs',
)]
class CreateSampleFavorisCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
        private DestinationRepository $destinationRepository
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $users = $this->userRepository->findAll();
        $destinations = $this->destinationRepository->findAll();

        if (empty($users)) {
            $io->error('Aucun utilisateur trouvé. Créez d\'abord des utilisateurs.');
            return Command::FAILURE;
        }

        if (empty($destinations)) {
            $io->error('Aucune destination trouvée. Créez d\'abord des destinations.');
            return Command::FAILURE;
        }

        $favorisCreated = 0;

        foreach ($users as $user) {
            // Ajouter 2-4 favoris aléatoires par utilisateur
            $numFavoris = rand(2, 4);
            $randomDestinations = array_rand($destinations, $numFavoris);
            
            if (!is_array($randomDestinations)) {
                $randomDestinations = [$randomDestinations];
            }

            foreach ($randomDestinations as $index) {
                $destination = $destinations[$index];
                
                // Vérifier si le favori existe déjà
                $existingFavori = $this->entityManager->getRepository(Favori::class)
                    ->findByUserAndDestination($user, $destination);
                
                if (!$existingFavori) {
                    $favori = new Favori();
                    $favori->setUser($user);
                    $favori->setDestination($destination);
                    
                    // Ajouter une note aléatoire
                    $notes = [
                        'J\'adorerais visiter cet endroit !',
                        'Parfait pour mes prochaines vacances',
                        'Un rêve de voyage',
                        'À ajouter à ma liste de souhaits',
                        'Magnifique destination',
                        'Parfait pour une escapade',
                        'Un must-see !',
                        'Idéal pour se ressourcer'
                    ];
                    
                    if (rand(0, 1)) {
                        $favori->setNote($notes[array_rand($notes)]);
                    }
                    
                    // Rendre certains favoris publics
                    $favori->setIsPublic(rand(0, 1) == 1);
                    
                    $this->entityManager->persist($favori);
                    $favorisCreated++;
                }
            }
        }

        $this->entityManager->flush();

        $io->success(sprintf('%d favoris d\'exemple ont été créés avec succès !', $favorisCreated));

        return Command::SUCCESS;
    }
} 