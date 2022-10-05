<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Messagerie;
use App\Entity\Messages;
use \DateTime;

class MessagerieController extends AbstractController
{
    /**
     * @Route("/messagerie/{id}", name="app_messagerie")
     */
    public function index($id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $useract=$this->getUser()->getId();
        $user = $em->getRepository(User::Class)->findOneBy(['id'=>$id]);
        $userCo= $em->getRepository(User::Class)->findOneBy(['id'=>$useract]);
        $desti = $em->getRepository(User::Class)->findOneBy(['id'=>$id]);
$messageries = $em->getRepository(Messagerie::class)->findOneBy(['expediteur'=>[$userCo,$user], 'destinataire'=>[$userCo,$user]]);



if(!$messageries){

    $messagerie = New Messagerie();
    $messagerie->setExpediteur($userCo);
    $messagerie->setDestinataire($user);
    $em->persist($messagerie);
    $em->flush();    
}else{
    $messagerie = $em->getRepository(Messagerie::class)->findOneBy(['expediteur'=>[$userCo,$user], 'destinataire'=>[$userCo,$user]]);
    
   
}
$idMessagerie= $messagerie->getId();
$messages= $em->getRepository(Messages::class)->findBy(['messagerie'=>$idMessagerie]);



        return $this->render('messagerie/messagerie.html.twig', [
            'user'=> $user,
            'id'=>$id,
            'userCo'=> $userCo,
            "desti" => $desti,
            "messagerie"=>$messagerie,
            "idMessagerie"=>$idMessagerie,
            "messages"=>$messages,
        
            // 'messagerie'=> $messagerie,
            // 'messagerieUser'=> $messagerieUser,
            // 'test'=> $test
        ]);
    }


        /**
     * @Route("/sendMessage/{id}/{idMessagerie}", name="sendMessage")
     */
    public function sendMessage($id, $idMessagerie): Response
    {


        $em = $this->getDoctrine()->getManager();
        
      
        if (!empty($_POST['validate'])) {
            $messagerie=$em->getRepository(Messagerie::Class)->findOneBy(['id'=>$idMessagerie]);
            $userCo = $em->getRepository(User::Class)->findOneBy(['id'=>$this->getUser()->getId()]);
            $desti = $em->getRepository(User::Class)->findOneBy(['id'=>$id]);
            
  $message = New Messages();
  $message -> setMessagerie($messagerie);
  $message -> setUser($userCo);
  $message -> setMessage($_POST['message']);
  $message -> setDate(new Datetime());
  $message -> setIsRead(0);

  $em->persist($message);
  $em->flush();

  
  return $this->redirect($this->generateUrl('app_messagerie', ['id'=>$id]));

        }
        return $this->render('messagerie/messagerie.html.twig', [
            "desti" => $desti,
        ]);
    }
}
