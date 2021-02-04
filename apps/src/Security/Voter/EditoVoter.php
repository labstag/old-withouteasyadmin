<?php

namespace Labstag\Security\Voter;

use Labstag\Entity\Edito;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class EditoVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        unset($attribute);

        return !(!$subject instanceof Edito);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        unset($attribute, $subject, $token);

        return true;
    }
}
