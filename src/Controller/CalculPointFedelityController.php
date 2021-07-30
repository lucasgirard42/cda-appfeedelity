<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Customer;
use App\Entity\User;
use App\Form\CustomerType;
use App\Repository\CustomerRepository;
use Doctrine\ORM\Repository\RepositoryFactory;
use PhpParser\Node\Param;
use Symfony\Component\Console\Style\StyleInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class CalculPointFedelityController extends AbstractController
{
    /**
     * @Route("/calcul/point/fedelity", name="calcul_point_fedelity")
     */
    public function index(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        if (!($user instanceof UserInterface)) {
            return $this->redirectToRoute('app_login');
        }
        $customer = $user->getCustomers();
        if (!isset($customer)) {
            $customer = new Customer();
            return $this->redirectToRoute('customer_new');
        }

        return $this->render('calcul_point_fedelity/index.html.twig', [
            'controller_name' => 'CalculPointFedelityController',
            'customers' => $customer,
        ]);
        
    }

 
    /**
     * 
     */
    public function addPointFedelity(Request $request, Customer $customer): Response
    {
        
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $initialFelityPoint = $customer -> getFidelityPoint(0);
            $addFedelityPoint = $initialFelityPoint+1;                   // ajout automatique des point de fedelité sur la route Edit à réctifier 
            $customer->setFidelityPoint($addFedelityPoint);

            

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($customer);
            $entityManager->flush();
            return $this->redirectToRoute('customer_index');
        }

        return $this->render('calcul_point_fedelity/index.html.twig', [
            'customer' => $customer,
            'form' => $form->createView(),
        ]);        
    }

    

    /**
     * @Route("/test/{id}", name="test", methods={"GET","POST"})
     */

     public function test(Request $request, Customer $customer): Response
     {

        $init = 0;
        $initialFelityPoint = $customer -> getFidelityPoint(0);
        $addFedelityPoint = $initialFelityPoint +=1 ;   // ajout automatique des point de fedelité sur la route Edit à réctifier 
        
        $point = $customer -> getFidelityPoint(0);
        $mail = $customer -> getEmail();
        
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
 
                // if ($point <= 9 ) {
                //      $customer->setFidelityPoint($addFedelityPoint);
                // } else {
                //     $customer->setFidelityPoint($init);
                // }

               switch ($point) {
                  
                    case 9:
                        $customer->setFidelityPoint($init);
                        
                        break;
                    case 10:
                        # code...
                        break;
                   
                   default:
                   $customer->setFidelityPoint($addFedelityPoint);
                       break;
               }
                    
                    

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($customer);
                    $entityManager->flush();
                    return $this->redirectToRoute('calcul_point_fedelity');
                }
                
        return $this->render('calcul_point_fedelity/test.html.twig', [
            'customer' => $customer ,
            'form' => $form->createView(),
            
        ]);
     }

     /**
      * @Route("/verif", name="verif", methods={"GET","POST"})
      */

      public function verif(): Response
      {
        $verife = 11;

          if ($verife <= 10) {
               echo " coucou" ;
            
          }
          return $this->render('calcul_point_fedelity/verife.html.twig', [
            'verifes' => $verife,

           ] );
      }

}
