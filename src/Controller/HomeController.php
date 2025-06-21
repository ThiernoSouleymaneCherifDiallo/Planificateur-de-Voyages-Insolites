<?php

namespace App\Controller;

use App\Repository\DestinationRepository;
use App\Repository\FavoriRepository;
use App\Repository\PlanificationRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        DestinationRepository $destinationRepository,
        FavoriRepository $favoriRepository,
        PlanificationRepository $planificationRepository,
        UserRepository $userRepository
    ): Response {
        // Statistiques générales
        $totalDestinations = $destinationRepository->count([]);
        $totalUsers = $userRepository->count([]);
        $totalFavoris = $favoriRepository->count([]);
        $totalPlanifications = $planificationRepository->count([]);

        // Destinations populaires (les plus favorisées)
        $popularDestinations = $favoriRepository->findMostFavoritedDestinations(6);

        // Destinations récentes
        $recentDestinations = $destinationRepository->findBy([], ['createdAt' => 'DESC'], 6);

        // Favoris publics récents
        $recentPublicFavoris = $favoriRepository->findPublicFavoris(5);

        // Statistiques pour l'utilisateur connecté
        $userStats = null;
        if ($this->getUser()) {
            $user = $this->getUser();
            $userFavoris = $favoriRepository->count(['user' => $user]);
            $userPlanifications = $planificationRepository->count(['user' => $user]);
            $upcomingPlanifications = $planificationRepository->findUpcomingByUser($user);
            
            $userStats = [
                'favoris' => $userFavoris,
                'planifications' => $userPlanifications,
                'upcoming' => count($upcomingPlanifications)
            ];
        }

        return $this->render('home/index.html.twig', [
            'total_destinations' => $totalDestinations,
            'total_users' => $totalUsers,
            'total_favoris' => $totalFavoris,
            'total_planifications' => $totalPlanifications,
            'popular_destinations' => $popularDestinations,
            'recent_destinations' => $recentDestinations,
            'recent_public_favoris' => $recentPublicFavoris,
            'user_stats' => $userStats,
        ]);
    }

    #[Route('/about', name: 'app_about')]
    public function about(): Response
    {
        return $this->render('home/about.html.twig');
    }

    #[Route('/contact', name: 'app_contact')]
    public function contact(): Response
    {
        return $this->render('home/contact.html.twig');
    }
}
