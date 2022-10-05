<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use MercurySeries\FlashyBundle\FlashyNotifier;
use App\Entity\Trajet;
use App\Entity\Ville;
use App\Entity\Ecoles;
use App\Entity\User;
use App\Entity\Conditions;
use App\Entity\Reservation;
use App\Form\TrajetType;
use \DateTime;

class TrajetsController extends AbstractController
{
    /**
     * @Route("/ajouter_trajets", name="nvx_trajet")
     */
    public function newTrajet(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $trajets = New Trajet();
  

        $form = $this->createFormBuilder($trajets)
        ->add('departVille', TextType::class, [
            'label' => "Ville de départ : ",
        ])
        ->add('departLng', HiddenType::class, [
            'label' => false,
        ])
        ->add('departLat', HiddenType::class, [
            'label' => false,
        ])
        // ->add('villeDepart', EntityType::class, [
        //     'label' => "Ville de départ : ",
        //     'class' => Ville::class,
        //     'choice_label' => 'ville',
        // ])

        // ->add('villeArrive', EntityType::class, [
        //     'label' => "Ville d'arrive : ",
        //     'class' => Ville::class,
        //     'choice_label' => 'ville',
        // ])
        ->add('ecole', EntityType::class, [
            'label' => "Ecole d'arrivée : ",
            'class' => Ecoles::class,
            'choice_label' => 'libelle',
        ])

        ->add('nbPlace', ChoiceType::class, [
            'label' => "Nombre de places disponibles :",
            'choices'  => [
                '1' => 1,
                '2' => 2,
                '3' => 3,
                '4' => 4,
                '5' => 5,
            ]
            ])
        ->add('commentaire', TextType::class, [
            'label' => "Commentaire : ",
        ])
        ->add('date', DateType::class, [
            'widget' => 'single_text',
            "label"=> "Date : ",
            
        ])
  
        ->getForm();

        $form->handleRequest($request);
   
        if ($form->isSubmitted() && $form->isValid()) {
  
     $nombrePlace= intval($form->getData()->getNbPlace()); 
           $trajet = New Trajet();
  
            $trajet->setUser($this->getUser());
            $trajet->setDate($form->getData()->getDate());
            $trajet->setVilleDepart($form->getData()->getVilleDepart());
            $trajet->setVilleArrive($form->getData()->getVilleArrive());
            $trajet->setDepartVille($form->getData()->getDepartVille());
            $trajet->setDepartLat($form->getData()->getDepartLat());
            $trajet->setDepartLng($form->getData()->getDepartLng());
            $trajet->setEcole($form->getData()->getEcole());
            $trajet->setNbPlace($nombrePlace);
            $trajet->setCommentaire($form->getData()->getCommentaire());
        
            $em->persist($trajet);
            $em->flush();
            
            return $this->redirect($this->generateUrl('addCondition', ['id'=>$trajet->getId()]));
        }

        return $this->render('trajets/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

        /**
     * @Route("/ajouter_condition/{id}", name="addCondition")
     */
    public function addCondition(Request $request, $id, FlashyNotifier $flashy): Response
    {

        $em = $this->getDoctrine()->getManager();
        
        $conditions = $em->getRepository(Conditions::Class)->findBy(["id"=>$id]);

        if (!empty($_POST['validate'])) {

        foreach($_POST as $key => $value){
       
            if($key != 'id' && $key != 'validate'){
                $keySpace = str_replace("_", " ", $key);
                $cond = $em->getRepository(Conditions::Class)->findOneBy(["trajet"=>$id, "libelle"=> $keySpace]);
                $cond->setContent($value);
                $em-> persist($cond);
                $em->flush();
                     }
             }
             $flashy->info('Votre trajet à bien été ajouté !');
             return $this->redirectToRoute('app_home');
        }

        return $this->render('trajets/conditions.html.twig', [
           'conditions'=> $conditions,
           'id'=>$id
        ]);

    
     
    }

            /**
     * @Route("/creer_condition/{id}", name="creerCondition")
     */
    public function creerCondition(Request $request, $id): Response
    {
        $em = $this->getDoctrine()->getManager();
       
      
        $trajet = $em->getRepository(Trajet::Class)->findOneBy(["id"=>$id]);
     
        $em = $this->getDoctrine()->getManager();
        if (!empty($_POST['libelle'])) {
           
          $conditions = New Conditions();
          $conditions-> setLibelle($_POST['libelle']);
          $conditions->setTrajet($trajet);
          $em->persist($conditions);
          $em->flush();
         
          return $this->redirect($this->generateUrl('creerCondition', ['id'=>$id]));
        }
        $conditions = $em->getRepository(Conditions::Class)->findBy(["trajet"=>$id]);
        return $this->render('trajets/conditions.html.twig', [
            'conditions'=> $conditions,
            'id'=>$id
        ]);

    }



            /**
     * @Route("/liste-trajets", name="listTrajets")
     */
    public function listTrajets(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $conditions = $em->getRepository(Conditions::Class)->findAll();

        if (!empty($_POST['validate'])) {
           
            $ecole = $em->getRepository(Ecoles::Class)->findOneBy(["id"=>$_POST['ecoles']]);
            $ecoleLat=$ecole->getlat();
            $ecoleLng=$ecole->getlng();
   
           $trajets = $em->getRepository(Trajet::Class)->findOneBy(["departVille"=>$_POST['depart']]);
    
            $latitude =$_POST['latitudeDepart'];
            $longitude =$_POST['longitudeDepart'];
          
         $formule="(6366*acos(cos(radians($latitude))*cos(radians(`depart_lat`))*cos(radians(`depart_lng`) -radians($longitude))+sin(radians($latitude))*sin(radians(`depart_lat`))))";
        
       
           
         $sql=" SELECT id, depart_ville,$formule AS dist FROM trajet WHERE $formule<=".$_POST['km']." ORDER by dist ASC";

        $statement = $em->getConnection()->prepare($sql);
        $statement->execute();
        $selectVille = $statement->fetchAll();
       
       
        $ids= array();
        foreach($selectVille as $res){
            array_push($ids,$res['id']);
        }
      
        $trajet = $em->getRepository(Trajet::Class)->findById($ids);
        
                
            }
            

            return $this->render('trajets/liste-trajets.html.twig', [
                'conditions'=> $conditions,
                'trajet'=>$trajet,
                'selectVille'=>$selectVille
                
                ]);
    }


    
            /**
     * @Route("/réservation/{id}", name="reservation")
     */
    public function reservation(Request $request, $id,FlashyNotifier $flashy): Response
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::Class)->findOneBy(['id'=>$this->getUser()->getId()]);
        $trajet = $em->getRepository(Trajet::Class)->findOneBy(['id'=>$id]);
        
        $reservation = New Reservation();
        $reservation -> setTrajet($trajet);
        $reservation -> setUser($user);
        $reservation -> setConfirm(0);
        $reservation -> setIsRead(0);
        $em-> persist($reservation);
        $em-> flush();

        $flashy->info('Votre demande de réservation à bien été prise en compte !');
        return $this->redirectToRoute('app_home');
    }

      /**
     * @Route("/upReservation/{id}/{action}", name="upReservation")
     */
    public function upReservation(Request $request, $id, $action, FlashyNotifier $flashy)
    {
        $em = $this->getDoctrine()->getManager();
        $reservation = $em->getRepository(Reservation::Class)->findOneBy(['id'=>$id]);
     
        $trajet = $em->getRepository(Trajet::Class)->findOneBy(['id'=>$reservation->getTrajet()->getId()]);
        $places=$trajet->getNbPlace();
       
     
        if($action == 'update'){
            $etat=1;
            $message="Vous avez bien accepté la demande ";
            if($places > 0){
            $trajet->setNbPlace($places=$places -1);
            $em->persist($trajet);
            $em->flush();
            }else{
                $message='suce mes couilles !!';
            }
        }else{
            $etat=2;
            $message="Vous avez refusé la demande ";

        }
 
        $reservation->setConfirm($etat);
        $reservation->setIsRead(1);
        $em->persist($reservation);
        $em->flush();

        $flashy->info($message);
        return $this->redirectToRoute('app_home');
    }
}