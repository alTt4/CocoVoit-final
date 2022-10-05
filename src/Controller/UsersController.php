<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    /**
     * @Route("/users", name="app_users")
     */
    public function index(): Response
    {
        return $this->render('users/index.html.twig', [
            'controller_name' => 'UsersController',
        ]);
    }

    /**
     * @Route("/parametres", name="parametres")
     */
    public function parametres(): Response
    {
        return $this->render('users/parametres.html.twig', [
            'controller_name' => 'UsersController',
        ]);
    }

       /**
     * @Route("/supprimer", name="supprimer")
     */
    public function supprimer(): Response
    {
        return $this->render('users/supprimerCompte.html.twig', [
            'controller_name' => 'UsersController',
        ]);
    }
}
