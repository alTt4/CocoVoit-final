<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\CallApiService;
use App\Entity\User;
use App\Entity\Ville;
use App\Entity\Trajet;
use App\Entity\Ecoles;
use App\Entity\Messagerie;
use App\Entity\Reservation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use \DateTime;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Session\Session;
use MercurySeries\FlashyBundle\FlashyNotifier;

class HomeController extends AbstractController
{

        /**
     * @Route("/testConnexion", name="testConnexion")
     */
    public function testConnexion()
    {

       
        $time = time();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $session = new Session();
        $session->set('lastCo',$time);
        $user->setTimeCo($time);
        $em->persist($user);
        $em->flush();
       

       return $this->redirect($this->generateUrl('app_home'));
    }

    /**
     * @Route("/", name="app_home")
     */
    public function index(CallApiService $api, Request $request, FlashyNotifier $flashy): Response
    {
        $em = $this->getDoctrine()->getManager();
        $user=$em->getRepository(User::Class)->findby(['id'=>$this->getUser()]);
        $ecoles = $em->getRepository(Ecoles::Class)->findAll();
       
        $user=$this->getUser();
       
         
        // $session = new Session();
        // if($session->get('lastCo') != $user->getTimeCo())
        // {
        //     $flashy->info("Inscription réussi !");
        //     return $this->redirect($this->generateUrl('app_home'));
      
        // }
        
       
    
            if (!empty($_POST['validate'])) {


        $ecole = $em->getRepository(Ecoles::Class)->findOneBy(["id"=>$_POST['ecoles']]);
        $ecoleLat=$ecole->getlat();
        $ecoleLng=$ecole->getlng();

       $trajets = $em->getRepository(Trajet::Class)->findOneBy(["departVille"=>$_POST['depart']]);

        $latitude =$_POST['latitudeDepart'];
        $longitude =$_POST['longitudeDepart'];
    
     $formule="(6366*acos(cos(radians($latitude))*cos(radians(`lat`))*cos(radians(`lon`) -radians($longitude))+sin(radians($latitude))*sin(radians(`lat`))))";
    
   
      
     $sql="SELECT id, depart_ville,(6366*acos(cos(radians(49.201648))*cos(radians(`depart_lat`))*cos(radians(`depart_lng`) -radians(6.038682))+sin(radians(49.201648))*sin(radians(`depart_lat`)))) AS dist FROM trajet WHERE (6366*acos(cos(radians(49.201648))*cos(radians(`depart_lat`))*cos(radians(`depart_lng`) -radians(6.038682))+sin(radians(49.201648))*sin(radians(`depart_lat`))))<=".$_POST['km']." ORDER by dist ASC";
    $statement = $em->getConnection()->prepare($sql);
    $statement->execute();
    $selectVille = $statement->fetchAll();

    $ids= array();
    foreach($selectVille as $res){
        array_push($ids,$res['id']);
    }
    $trajet = $em->getRepository(Trajet::Class)->findById($ids);
    

            return $this->redirect($this->generateUrl('listTrajets',[]));
        }

        return $this->render('home/index.html.twig', [
            // 'form' => $form->createView(),
            'ecoles'=>$ecoles,
        ]);
    }

    /**
     * @Route("/importerVille", name="importerVille")
     */
    public function importerVille(Request $request)
    {
      
        $em = $this->getDoctrine()->getManager();
        $repo = $this->getDoctrine()->getRepository(User::class);
        $user = $repo->findOneBy(array('id'=>3));

        $i = 0;
        if (($handle = fopen("cities2.csv", "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 5000, ";")) !== FALSE) {
              $i++;
              if($i > 1)
              {
              $ville =  $data[4];

              $CP = $data[3];
              $latitude = $data[6];
              $logitude = $data[7];
            
            
              
            
              $villes = new Ville();
            //   $client->setCreated(new Datetime());
              
              $villes->setVille($ville);
              $villes->setCp($CP);
              $villes->setLatitude($latitude);
              $villes->setLongitude($logitude);
     
           


              $em->persist($villes);
              $em->flush();
                  }
            }
            fclose($handle);
        }
    }
     /**
     * @Route("/countNotif", name="countNotif")
     */
    public function countNotif(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user=$this->getUser();
        $trajet = $em->getRepository(Trajet::Class)->findBy(['user'=>$user]);
     
        $reservation = $em->getRepository(Reservation::Class)->findBy(['trajet'=>$trajet, 'confirm'=>0]);

        $countreservation = count($reservation);
        $chaine = '<span class="nombreNotifs" style="float:right; color: white; background-color: #fe5c2d !important; border-radius: 30px; position: relative; right: 21px; top: 15px;">
        &nbsp;'.$countreservation.'&nbsp;
    </span>';
        return new response ($chaine); 
    }
         /**
     * @Route("/countMessage", name="countMessage")
     */
    public function countMessage(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user=$this->getUser();
        $userMessage = $em->getRepository(Messagerie::Class)->findMessage($user);
        // dd($reservation);
     
        // $reservation = $em->getRepository(Reservation::Class)->findBy(['trajet'=>$trajet, 'confirm'=>0]);

        // $countreservation = count($reservation);
        $chaine = '<span class="nombreNotifs" style="float:right; color: white; background-color: #fe5c2d !important; border-radius: 30px; position: relative; right: 21px; top: 15px;">
        &nbsp;'.'1'.'&nbsp;
    </span>';
    //     $em = $this->getDoctrine()->getManager();
    //     $user=$this->getUser();
       
     
    //     $reservation = $em->getRepository(Reservation::Class)->findBy(['trajet'=>$trajet, 'confirm'=>0]);

    //     $countreservation = count($reservation);
    //     $chaine = '<span class="nombreNotifs" style="float:right; color: white; background-color: #fe5c2d !important; border-radius: 30px; position: relative; right: 21px; top: 15px;">
    //     &nbsp;'.$countreservation.'&nbsp;
    // </span>';
        return new response ($chaine); 
    }
     /**
     * @Route("/notif", name="notif")
     */
    public function notif(Request $request)
    {
        // $this->get('twig')->addGlobal('reservation', $reservation);
        $em = $this->getDoctrine()->getManager();
        $chaine = "";
       
        $user=$this->getUser();

        $trajet = $em->getRepository(Trajet::Class)->findBy(['user'=>$user]);
    

        $reservation=  $em->getRepository(Reservation::Class)->findBy(['trajet'=>$trajet]);
    if(count($reservation)!=0){
        foreach($reservation as $res){
            $url1=$this->generateUrl('upReservation', ['id'=>$res->getId(), 'action'=> 'update']);
            $url2=$this->generateUrl('upReservation', ['id'=>$res->getId(), 'action'=> 'delete']);
            if($res->getConfirm()==0){
                if(empty($res->getUser()->getImage())){
                $chaine .= '<div>
                        <img src="https://deamonerp.fr/cocoVoit/public/images/iconeprofil.png" style="width:40px; border-radius:50%; margin-right:5px">
                        '.$res->getUser()->getPrenom().' souhaite réserver une place sur votre trajet.
                        <a href="'.$url1.'">
                        <button style="position:relative; bottom:12px" type="button" class="btn btn-success">Accepter</button>
                        </a>
                        <a href="'.$url2.'">
                        <button style="position:relative; bottom:12px" type="button" class="btn btn-danger">Refuser</button>
                        </a>
                    </div>
                    <hr style="border: 1px solid #fe5c2d;">';
                }else{
                $chaine .= '<div>
                        <img src="/cocoVoit/public/uploads/'.$res->getUser()->getImage().'" style="width:40px; border-radius:50%; margin-right:5px">
                        '.$res->getUser()->getPrenom().' souhaite réserver une place sur votre trajet.
                        <a href="'.$url1.'">
                        <button style="position:relative; bottom:12px" type="button" class="btn btn-success">Accepter</button>
                        </a>
                        <a href="'.$url2.'">
                        <button style="position:relative; bottom:12px" type="button" class="btn btn-danger">Refuser</button>
                        </a>
                    </div>
                    <hr style="border: 1px solid #fe5c2d;">';
                }
            }else{
                if($res->getConfirm()==1){
                $chaine .= '<div>
                     <img src="../images/iconeprofil.png" style="width:40px; border-radius:50%; margin-right:5px">
                     '.$res->getUser()->getPrenom().' souhaite réserver une place sur votre trajet.
                     <span style="color:green"> Vous avez déjà accepté cette demande.
                 </div>
                <hr style="border: 1px solid #fe5c2d;">';
                }else{
                $chaine .= '<div>
                     <img src="../images/iconeprofil.png" style="width:40px; border-radius:50%; margin-right:5px">
                     '.$res->getUser()->getPrenom().' souhaite réserver une place sur votre trajet.
                     <span style="color:red"> Vous avez déjà refusé cette demande.
                 </div>
                <hr style="border: 1px solid #fe5c2d;">';
                }
            }
        }
    }else{
        $chaine .= '<div>Vous n\'avez aucune notification.</div>';
    }
       
        return new response ($chaine); 
    }

         /**
     * @Route("/message", name="message")
     */
    public function message(Request $request)
    {
        // $this->get('twig')->addGlobal('reservation', $reservation);
        $em = $this->getDoctrine()->getManager();
        $userId=$this->getUser()->getId();
       
       
        

        // $messagerie = $em->getRepository(Messagerie::class)->findBy([['expediteur', 'destinataire']=>$userCo]);

        $sql= 'SELECT * FROM messagerie Where destinataire_id != '.$userId.' AND expediteur_id =  '.$userId.' OR destinataire_id = '.$userId.' AND expediteur_id !=  '.$userId.'  GROUP BY destinataire_id,expediteur_id';
        $statement = $em->getConnection()->prepare($sql);
        $statement->execute();
        $mess = $statement->fetchAll();
       $chaine="";
        foreach($mess as $res){
            if( $res['destinataire_id'] == $userId){
            $url=$this->generateUrl('app_messagerie', ['id'=>$res['expediteur_id']]);
            $user=$em->getRepository(User::Class)->findOneBy(['id'=>$res['expediteur_id']]);
        }else{
       
            $url=$this->generateUrl('app_messagerie', ['id'=>$res['destinataire_id']]);
            $user=$em->getRepository(User::Class)->findOneBy(['id'=>$res['destinataire_id']]);  
         
        }
        
        $prenom=$user->getPrenom();
        if(empty($user->getImage())){
            $chaine .= '  <div>
                <img src="https://deamonerp.fr/cocoVoit/public/images/iconeprofil.png" style="width:40px; border-radius:50%; margin-right:5px">
                        '.$prenom.' vous a envoyé un message.
                <a href="'.$url.'">
                <button style="position:relative; bottom:12px" type="button" class="btn btn-primary">Voir la conversation</button>
            </a>
                </div>
            <hr style="border: 1px solid #fe5c2d;">';
        }else{
            $chaine .= '  <div>
                <img src="/cocoVoit/public/uploads/'.$user->getImage().'"style="width:40px; border-radius:50%; margin-right:5px">
                            '.$prenom.' vous a envoyé un message.
                    <a href="'.$url.'">
                    <button style="position:relative; bottom:12px" type="button" class="btn btn-primary">Voir la conversation</button>
                </a>
                    </div>
                <hr style="border: 1px solid #fe5c2d;">';
        }
        };
       
        return new response ($chaine); 
    }

}
