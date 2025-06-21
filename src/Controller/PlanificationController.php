<?php

namespace App\Controller;

use App\Entity\Planification;
use App\Form\PlanificationTypeForm;
use App\Repository\PlanificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/planification')]
#[IsGranted('ROLE_USER')]
class PlanificationController extends AbstractController
{
    #[Route('/', name: 'app_planification_index', methods: ['GET'])]
    public function index(PlanificationRepository $planificationRepository): Response
    {
        $user = $this->getUser();
        
        // Récupérer les planifications par catégorie
        $planificationsAVenir = $planificationRepository->findUpcomingByUser($user);
        $planificationsEnCours = $planificationRepository->findCurrentByUser($user);
        $planificationsTerminees = $planificationRepository->findCompletedByUser($user);
        
        return $this->render('planification/index.html.twig', [
            'planifications_a_venir' => $planificationsAVenir,
            'planifications_en_cours' => $planificationsEnCours,
            'planifications_terminees' => $planificationsTerminees,
        ]);
    }

    #[Route('/new', name: 'app_planification_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $planification = new Planification();
        $planification->setUser($this->getUser());
        
        $form = $this->createForm(PlanificationTypeForm::class, $planification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $planification->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->persist($planification);
            $entityManager->flush();

            $this->addFlash('success', 'Votre planification a été créée avec succès !');
            return $this->redirectToRoute('app_planification_index');
        }

        return $this->render('planification/new.html.twig', [
            'planification' => $planification,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_planification_show', methods: ['GET'])]
    public function show(Planification $planification): Response
    {
        // Vérifier que l'utilisateur connecté est bien le propriétaire de la planification
        if ($planification->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette planification.');
        }

        return $this->render('planification/show.html.twig', [
            'planification' => $planification,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_planification_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Planification $planification, EntityManagerInterface $entityManager): Response
    {
        // Vérifier que l'utilisateur connecté est bien le propriétaire de la planification
        if ($planification->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette planification.');
        }

        $form = $this->createForm(PlanificationTypeForm::class, $planification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $planification->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->flush();

            $this->addFlash('success', 'Votre planification a été modifiée avec succès !');
            return $this->redirectToRoute('app_planification_show', ['id' => $planification->getId()]);
        }

        return $this->render('planification/edit.html.twig', [
            'planification' => $planification,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_planification_delete', methods: ['POST'])]
    public function delete(Request $request, Planification $planification, EntityManagerInterface $entityManager): Response
    {
        // Vérifier que l'utilisateur connecté est bien le propriétaire de la planification
        if ($planification->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette planification.');
        }

        if ($this->isCsrfTokenValid('delete'.$planification->getId(), $request->request->get('_token'))) {
            $entityManager->remove($planification);
            $entityManager->flush();
            
            $this->addFlash('success', 'Votre planification a été supprimée avec succès !');
        }

        return $this->redirectToRoute('app_planification_index');
    }

    #[Route('/{id}/status/{status}', name: 'app_planification_change_status', methods: ['POST'])]
    public function changeStatus(Request $request, Planification $planification, string $status, EntityManagerInterface $entityManager): Response
    {
        // Vérifier que l'utilisateur connecté est bien le propriétaire de la planification
        if ($planification->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'avez pas accès à cette planification.');
        }

        if ($this->isCsrfTokenValid('status'.$planification->getId(), $request->request->get('_token'))) {
            $planification->setStatut($status);
            $planification->setUpdatedAt(new \DateTimeImmutable());
            $entityManager->flush();
            
            $this->addFlash('success', 'Le statut de votre planification a été mis à jour !');
        }

        return $this->redirectToRoute('app_planification_show', ['id' => $planification->getId()]);
    }
}
