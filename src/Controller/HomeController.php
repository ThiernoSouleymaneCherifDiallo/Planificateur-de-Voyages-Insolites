<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        $nom = "Etienne";

        dd($nom);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);

        }
        #[Route('/nom', name: 'app_nom', methods: ['POST','GET'])]
        public function nouvelle_fonction()
        {
                dd("Bonjour Etienne ");
        }   


//fonction avec route annotation et parametre
    #[Route('/nom/{nom}/{prenom}', name: 'app_nom_id', methods: ['POST','GET'])]
     public function nouvelle_fonction_id($nom, $prenom)
     {

        $context = [    
            'nom' => $nom,
            'prenom' => $prenom,
        ];
        return $this->render('home/name.html.twig', $context);
  
     }  

     // nouvelle fonction avec routing dans le yaml


    public function nouvelle_fonction_yaml()
    {
        return $this->render('home/name.html.twig', [
            'nom' => 'Etienne',
            'prenom' => 'Dupont',
        ]);
    }

}
