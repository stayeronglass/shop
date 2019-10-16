<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class SameUserVoter extends Voter
{


    protected function supports($attribute, $subject)  : bool
    {
        return $subject && method_exists($subject, 'getUser') && $subject->getUser();
    }



    protected function voteOnAttribute($attribute, $subject, TokenInterface $token) : bool
    {
        $user = $token->getUser();

        if ( !($user instanceof User) || (!$subject) || (!method_exists($subject, 'getUser')) || (!$subject->getUser())) {
            // the user must be logged in; if not, deny access
            return false;
        }

        return $user->getId() === $subject->getUser()->getId();
    }

}