<?php

namespace App\Security\Voter;

use App\Entity\Customer;
use App\Entity\User;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class CustomersVoter extends Voter
{
    const EDIT = 'EDIT_CUSTOMER'; 

    protected function supports(string $attribute, $subject)
    {
        return 
            $attribute === self::EDIT && 
            $subject instanceof Customer;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (
            !$user instanceof User ||
            !$subject instanceof Customer
        ) {
            return false;
        }

        return $subject->getUser()->getId() === $user->getId();
    }
}