<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Entity\Ville;
use App\Entity\Admin;
use MercurySeries\FlashyBundle\FlashyNotifier;


class RegistrationController extends AbstractController
{
    /**
     * @Route("/inscription", name="inscription")
     */
    public function register(Request $request, UserPasswordEncoderInterface $userPasswordEncoder, EntityManagerInterface $entityManager, FlashyNotifier $flashy): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);

        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {
            //SetRoles
            $user->setRoles(['ROLE_USER']);

            // Set image de profil
            $image = $form->get('image')->getData();
            if ($image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
               
                $newFilename = uniqid().'.'.$image->guessExtension();

                
                try {
                    $image->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    echo("erreur");
                }

                $user->setImage($newFilename);
                
            }
            // encode the plain password
            $userform = $form->getData();
           
            $user->setPassword(
            $userPasswordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
            )
            );
            $admin = $em->getRepository(Admin::class)->findOneBy(['code'=>$_POST['code'], 'utilise'=>0]);
            if($admin){
               
                $admin->setUtilise(1);
               
                $entityManager->persist($user);
                $entityManager->flush();
                $flashy->info("Inscription réussi !");
                return $this->redirectToRoute('app_home');
           
            }else{
                $flashy->info("Votre code d'accès est érroné ");
              return $this->redirectToRoute('app_home');
            }
           

          
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView()
        ]);
    }
}
