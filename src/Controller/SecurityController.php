<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\User;


class SecurityController extends AbstractController
{

     public function generateToken()
    {
        //same token generator that is used by FOSUserBundle wich is this:
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }

    /**
     * @Route("/connexion", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
       
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
            
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
     ;


        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        // throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
        return $this->redirect($this->generateUrl('app_login'));
    }

       /**
     * @Route("/erreurConnexion", name="erreurConnexion")
     */
    public function erreurConnexion()
    {
        return $this->render('home/erreurConnexion.html.twig', [
            
        ]);
        
     }

      /**
     * @Route("/oubli-pass", name="oubliPass")
     */
    public function oubliPass(Request $request):Reponse
    {
        $em = $this->getDoctrine()->getManager();
      
  

        $form = $this->createFormBuilder()
            ->add('email', EmailType::class)
            ->add('envoyer', SubmitType::class)
            ->getForm();

            $form->handleRequest($request);
       
            if ($form->isSubmitted() && $form->isValid()) {

                $donnees = $form->getData();
                $user = $users->findOneBy(['email'=>$donnees['email']]);
                dd($user);
            }

         

            return $this->render('security/forgotten_password.html.twig', [
                'form' => $form->createView(),
             
            ]);

    }


}
