<?php

namespace Labstag\Security\Voter;

use Labstag\Entity\EmailUser;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class EmailUserVoter extends Voter
{
    protected function supports($attribute, $subject): bool
    {
        unset($attribute);

        return $subject instanceof EmailUser;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        unset($attribute, $subject, $token);

        return true;
    }
}
