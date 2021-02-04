<?php

namespace Labstag\Security\Voter;

use Labstag\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        unset($attribute);
        if (!$subject instanceof User) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        unset($attribute, $subject, $token);

        return true;
    }
}
