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
use Symfony\Component\Cache\Adapter\ParameterNormalizer;
use Symfony\Component\Console\Style\StyleInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;



class CalculPointFedelityController extends AbstractController
{
    /**
     * @Route("/calcul/point/fedelity", name="calcul_point_fedelity",  methods={"GET","POST"})
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
     * @Route("/addPoint/{id}", name="addPoint", methods={"GET","POST"})
     */

     public function addPoint(Customer $customer, MailerInterface $mailer): Response
     {
         /** @var User $user */
        $user = $this->getUser();

        $init = 0;
        $initialFelityPoint = $customer -> getFidelityPoint(0);
        $addFedelityPoint = $initialFelityPoint +=1 ;   // ajout automatique des point de fedelité sur la route Edit à réctifier 
        
        $point = $customer -> getFidelityPoint(0);
        
        $mailUser = $user->getEmail(); 
        $mailCustomer = $customer -> getEmail();

        $email = (new Email())
        ->from($mailUser)
        ->to($mailCustomer)
        //->cc('cc@example.com')
        //->bcc('bcc@example.com')
        //->replyTo('fabien@example.com')
        //->priority(Email::PRIORITY_HIGH)
        ->subject('Bravo vous avez recu une reduction')
        ->text('félicitation vous avez recu 10 point de fidélité, vous avez le droit un une réduction 
        de 10% pour votre prochaine séance!')
        ->html('<p>félicitation vous avez recu 10 point de fidélité, vous avez le droit un une réduction 
                de 10% pour votre prochaine séance</p>');

        // $form = $this->createForm(CustomerType::class, $customer);
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()){
 
                // if ($point <= 9 ) {
                   
                //      $customer->setFidelityPoint($addFedelityPoint);
                // } else {
                //     $customer->setFidelityPoint($init);
                // }

               switch ($point) {
                    case 9:
                        $mailer->send($email);
                        $customer->setFidelityPoint($init);
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
