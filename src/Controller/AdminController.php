<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Admin;
use App\Entity\User;
use MercurySeries\FlashyBundle\FlashyNotifier;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $adminToken= $em->getRepository(Admin::Class)->findAll();


        return $this->render('admin/index.html.twig', [
            "adminToken"=>$adminToken,
        ]);
    }


     /**
     * @Route("/admin/addToken", name="addToken")
     */
    public function addToken(): Response
    {
            $em = $this->getDoctrine()->getManager();
           
            $caracteres = '0123456789';
          $maxchar=$_POST['max'];
        
            $longueurMax = strlen($caracteres);
            $chaineAleatoire = '';
            for ($i = 0; $i < $maxchar; $i++)
            {
            $chaineAleatoire .= $caracteres[rand(0, $longueurMax - 1)];
            }
           

          

            $admin = New Admin(); 
            $admin-> setCode($chaineAleatoire);
            $admin->setUtilise(0);
            $em->persist($admin);
            $em->flush();

          
            $adminToken= $em->getRepository(Admin::Class)->findAll();

            return $this->render('admin/index.html.twig', [
                "adminToken"=>$adminToken,
            ]);

    }

       /**
     * @Route("/admin/utilisateurs", name="adminUser")
     */
    public function adminUser(): Response
    {
        $em=$this->getDoctrine()->getManager();
        $users=$em->getRepository(User::class)->findAll();



        return $this->render('admin/user.html.twig', [
            'users'=>$users,
            "adminToken"=>$em->getRepository(Admin::Class)->findAll(),
            
        ]);

    }

           /**
     * @Route("/admin/upRoles/{id}", name="upRoles")
     */
    public function upRoles($id, FlashyNotifier $flashy): Response
    {
$em=$this->getDoctrine()->getManager();
$user= $em ->getRepository(User::class)->findOneby(['id'=>$id]);
$user->setRoles(['ROLE_ADMIN']);
$em->persist($user);
$em->flush();
$flashy->info("Top  ");

   
    return $this->render('admin/user.html.twig', [
        'users'=>$em->getRepository(User::class)->findAll(),
        'adminToken'=>$em->getRepository(Admin::Class)->findAll(),
        'user'=>$user
        
    ]);
}
  /**
     * @Route("/admin/downRoles/{id}", name="downRoles")
     */
    public function downRoles($id, FlashyNotifier $flashy): Response
    {
$em=$this->getDoctrine()->getManager();
$user= $em ->getRepository(User::class)->findOneby(['id'=>$id]);
$user->setRoles(['ROLE_USER']);
$em->persist($user);
$em->flush();
$flashy->info("Top  ");

   
    return $this->render('admin/user.html.twig', [
        'users'=>$em->getRepository(User::class)->findAll(),
        'adminToken'=>$em->getRepository(Admin::Class)->findAll(),
        'user'=>$user
        
    ]);
}
}
