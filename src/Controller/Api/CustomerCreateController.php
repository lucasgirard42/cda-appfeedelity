<?php

namespace App\Controller\Api;

use App\Entity\Customer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Security;







class CustomerCreateController extends AbstractController
{
    private $security;

    public function __construct(Security $security )
    {
        $this->security = $security; 
    }
    
    public function __invoke(Customer $data)
    {
        $data->setUser($this->security->getUser());
        
        return $data; 
    }
}