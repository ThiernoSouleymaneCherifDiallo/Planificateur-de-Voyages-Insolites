<?php

namespace App\Controller;

use App\Entity\Preference;
use App\Form\PreferenceType;
use App\Repository\PreferenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/preferences')]
#[IsGranted('ROLE_USER')]
class PreferenceController extends AbstractController
{
    #[Route('/', name: 'app_preference_index', methods: ['GET'])]
    public function index(PreferenceRepository $preferenceRepository): Response
    {
        $user = $this->getUser();
        $preference = $preferenceRepository->findOrCreateByUser($user);

        return $this->render('preference/index.html.twig', [
            'preference' => $preference,
        ]);
    }

    #[Route('/edit', name: 'app_preference_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PreferenceRepository $preferenceRepository, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $preference = $preferenceRepository->findOrCreateByUser($user);
        
        $form = $this->createForm(PreferenceType::class, $preference);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Vos préférences ont été mises à jour avec succès !');

            return $this->redirectToRoute('app_preference_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('preference/edit.html.twig', [
            'preference' => $preference,
            'form' => $form,
        ]);
    }
} 