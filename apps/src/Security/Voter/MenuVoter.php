<?php

namespace Labstag\Security\Voter;

use Labstag\Entity\Menu;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class MenuVoter extends Voter
{
    protected function supports($attribute, $subject)
    {
        unset($attribute);

        return !(!$subject instanceof Menu);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        unset($attribute, $subject, $token);

        return true;
    }
}
