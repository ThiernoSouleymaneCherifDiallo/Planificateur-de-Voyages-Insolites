<?php

namespace App\Controller;

use App\Repository\DestinationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublicDestinationController extends AbstractController
{
    #[Route('/destinations', name: 'app_public_destinations')]
    public function index(DestinationRepository $destinationRepository): Response
    {
        $destinations = $destinationRepository->findAll();

        return $this->render('public_destination/index.html.twig', [
            'destinations' => $destinations,
        ]);
    }

    #[Route('/destinations/{id}', name: 'app_public_destination_show')]
    public function show(int $id, DestinationRepository $destinationRepository): Response
    {
        $destination = $destinationRepository->find($id);

        if (!$destination) {
            throw $this->createNotFoundException('Destination non trouvée');
        }

        return $this->render('public_destination/show.html.twig', [
            'destination' => $destination,
        ]);
    }
} 