<?php

namespace App\Controller;

use App\Entity\Favori;
use App\Entity\Destination;
use App\Repository\FavoriRepository;
use App\Repository\DestinationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/favoris')]
#[IsGranted('ROLE_USER')]
class FavoriController extends AbstractController
{
    #[Route('/', name: 'app_favori_index', methods: ['GET'])]
    public function index(FavoriRepository $favoriRepository): Response
    {
        $user = $this->getUser();
        $favoris = $favoriRepository->findByUser($user);
        
        return $this->render('favori/index.html.twig', [
            'favoris' => $favoris,
        ]);
    }

    #[Route('/add/{id}', name: 'app_favori_add', methods: ['POST'])]
    public function add(Request $request, Destination $destination, EntityManagerInterface $entityManager, FavoriRepository $favoriRepository): Response
    {
        $user = $this->getUser();
        
        // Vérifier si le favori existe déjà
        $existingFavori = $favoriRepository->findByUserAndDestination($user, $destination);
        
        if ($existingFavori) {
            $this->addFlash('info', 'Cette destination est déjà dans vos favoris !');
        } else {
            $favori = new Favori();
            $favori->setUser($user);
            $favori->setDestination($destination);
            
            // Récupérer la note si fournie
            $note = $request->request->get('note');
            if ($note) {
                $favori->setNote($note);
            }
            
            $entityManager->persist($favori);
            $entityManager->flush();
            
            $this->addFlash('success', 'Destination ajoutée aux favoris !');
        }
        
        // Rediriger vers la page précédente ou la liste des destinations
        $referer = $request->headers->get('referer');
        return $referer ? $this->redirect($referer) : $this->redirectToRoute('app_public_destinations');
    }

    #[Route('/remove/{id}', name: 'app_favori_remove', methods: ['POST'])]
    public function remove(Request $request, Destination $destination, EntityManagerInterface $entityManager, FavoriRepository $favoriRepository): Response
    {
        $user = $this->getUser();
        $favori = $favoriRepository->findByUserAndDestination($user, $destination);
        
        if ($favori) {
            $entityManager->remove($favori);
            $entityManager->flush();
            $this->addFlash('success', 'Destination retirée des favoris !');
        } else {
            $this->addFlash('error', 'Cette destination n\'était pas dans vos favoris.');
        }
        
        // Rediriger vers la page précédente ou la liste des favoris
        $referer = $request->headers->get('referer');
        return $referer ? $this->redirect($referer) : $this->redirectToRoute('app_favori_index');
    }

    #[Route('/toggle/{id}', name: 'app_favori_toggle', methods: ['POST'])]
    public function toggle(Request $request, Destination $destination, EntityManagerInterface $entityManager, FavoriRepository $favoriRepository): Response
    {
        $user = $this->getUser();
        $favori = $favoriRepository->findByUserAndDestination($user, $destination);
        
        if ($favori) {
            // Retirer des favoris
            $entityManager->remove($favori);
            $entityManager->flush();
            $this->addFlash('success', 'Destination retirée des favoris !');
        } else {
            // Ajouter aux favoris
            $favori = new Favori();
            $favori->setUser($user);
            $favori->setDestination($destination);
            
            $entityManager->persist($favori);
            $entityManager->flush();
            $this->addFlash('success', 'Destination ajoutée aux favoris !');
        }
        
        // Rediriger vers la page précédente
        $referer = $request->headers->get('referer');
        return $referer ? $this->redirect($referer) : $this->redirectToRoute('app_public_destinations');
    }

    #[Route('/{id}/note', name: 'app_favori_note', methods: ['POST'])]
    public function updateNote(Request $request, Favori $favori, EntityManagerInterface $entityManager): Response
    {
        // Vérifier que l'utilisateur connecté est bien le propriétaire du favori
        if ($favori->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à ce favori.');
        }
        
        $note = $request->request->get('note');
        $favori->setNote($note);
        
        $entityManager->flush();
        
        $this->addFlash('success', 'Note mise à jour !');
        
        return $this->redirectToRoute('app_favori_index');
    }

    #[Route('/{id}/public', name: 'app_favori_toggle_public', methods: ['POST'])]
    public function togglePublic(Request $request, Favori $favori, EntityManagerInterface $entityManager): Response
    {
        // Vérifier que l'utilisateur connecté est bien le propriétaire du favori
        if ($favori->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à ce favori.');
        }
        
        $favori->setIsPublic(!$favori->isPublic());
        $entityManager->flush();
        
        $status = $favori->isPublic() ? 'public' : 'privé';
        $this->addFlash('success', 'Favori rendu ' . $status . ' !');
        
        return $this->redirectToRoute('app_favori_index');
    }

    #[Route('/community', name: 'app_favori_community', methods: ['GET'])]
    public function community(FavoriRepository $favoriRepository): Response
    {
        $publicFavoris = $favoriRepository->findPublicFavoris(20);
        $mostFavorited = $favoriRepository->findMostFavoritedDestinations(10);
        
        return $this->render('favori/community.html.twig', [
            'public_favoris' => $publicFavoris,
            'most_favorited' => $mostFavorited,
        ]);
    }

    #[Route('/ajax/check/{id}', name: 'app_favori_ajax_check', methods: ['GET'])]
    public function ajaxCheck(Destination $destination, FavoriRepository $favoriRepository): Response
    {
        $user = $this->getUser();
        $isFavori = $favoriRepository->isFavori($user, $destination);
        
        return $this->json([
            'isFavori' => $isFavori,
            'destinationId' => $destination->getId()
        ]);
    }
} 