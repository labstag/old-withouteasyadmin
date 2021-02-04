<?php

namespace Labstag\Security\Voter;

use Labstag\Entity\AdresseUser;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class AdresseUserVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        unset($attribute);
        if (!$subject instanceof AdresseUser) {
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
