<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\User;
use App\Form\CustomerType;
use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

/**
 * @Route("/customer")
 */
class CustomerController extends AbstractController
{
    /**
     * @Route("/", name="customer_index", methods={"GET"})
     */
    public function index(CustomerRepository $customerRepository): Response
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

        return $this->render('customer/index.html.twig', [
            'customers' => $customer,
        ]);
    }




    /**
     * @Route("/new", name="customer_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $user = $this->getUser();

        // Redirect users who already have a customer to edit form
        if ($user->getCustomers() instanceof Customer) {
            $customerId = $user->getCustomers()->getId();           /////////////// -------------------  ////////////
            return $this->redirectToRoute('customer_edit', [
                'id' => $customerId,

            ]);
        }


        $customer = new Customer();


        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $customer->setUser($user);                                  ////// <------------- //////

            $entityManager->persist($customer);
            $entityManager->flush();

            return $this->redirectToRoute('customer_show_user');
        }

        return $this->render('customer/new.html.twig', [
            'customer' => $customer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/", name="customer_show_user", methods={"GET"})
     */
    public function showUser(): Response
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

        return $this->render('customer/show.html.twig', [
            'customer' => $customer,
        ]);
    }


    /**
     * @Route("/{id}", name="customer_show", methods={"GET"})
     */
    public function show(Customer $customer): Response
    {
        return $this->render('customer/show.html.twig', [
            'customer' => $customer,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="customer_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Customer $customer): Response
    {
        
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);
        
        
        
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($customer);
            $entityManager->flush();


            return $this->redirectToRoute('customer_index');

        }

        return $this->render('customer/edit.html.twig', [
            'customer' => $customer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="customer_delete", methods={"POST"})
     */
    public function delete(Request $request, Customer $customer): Response
    {
        if ($this->isCsrfTokenValid('delete' . $customer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($customer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('customer_index');
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
       ->text('Sending emails is fun again!')
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
                    return $this->redirectToRoute('customer_index');

    }
}
