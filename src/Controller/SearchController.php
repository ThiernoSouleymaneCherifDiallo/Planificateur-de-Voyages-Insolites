<?php

namespace App\Controller;

use App\Repository\DestinationRepository;
use App\Repository\PreferenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/search')]
#[IsGranted('ROLE_USER')]
class SearchController extends AbstractController
{
    #[Route('/destinations', name: 'app_search_destinations', methods: ['GET'])]
    public function searchDestinations(
        Request $request, 
        PreferenceRepository $preferenceRepository, 
        DestinationRepository $destinationRepository
    ): Response {
        $user = $this->getUser();
        $preference = $preferenceRepository->findOrCreateByUser($user);
        
        // Récupérer les destinations selon les préférences
        $destinations = $destinationRepository->findByPreferences($preference);
        
        // Si aucune destination trouvée, afficher toutes les destinations
        if (empty($destinations)) {
            $destinations = $destinationRepository->findAll();
            $this->addFlash('info', 'Aucune destination ne correspond exactement à vos préférences. Voici toutes nos destinations :');
        } else {
            $this->addFlash('success', count($destinations) . ' destination(s) trouvée(s) selon vos préférences !');
        }

        return $this->render('search/destinations.html.twig', [
            'destinations' => $destinations,
            'preference' => $preference,
        ]);
    }

    #[Route('/destinations/advanced', name: 'app_search_advanced', methods: ['GET', 'POST'])]
    public function advancedSearch(
        Request $request, 
        DestinationRepository $destinationRepository
    ): Response {
        $criteria = [];
        
        // Récupérer les critères de recherche depuis la requête
        if ($request->query->get('climate')) {
            $criteria['climate'] = $request->query->get('climate');
        }
        if ($request->query->get('type')) {
            $criteria['type'] = $request->query->get('type');
        }
        if ($request->query->get('budgetLevel')) {
            $criteria['budgetLevel'] = (int) $request->query->get('budgetLevel');
        }
        if ($request->query->get('country')) {
            $criteria['country'] = $request->query->get('country');
        }

        $destinations = [];
        if (!empty($criteria)) {
            $destinations = $destinationRepository->searchDestinations($criteria);
        }

        return $this->render('search/advanced.html.twig', [
            'destinations' => $destinations,
            'criteria' => $criteria,
        ]);
    }
} 