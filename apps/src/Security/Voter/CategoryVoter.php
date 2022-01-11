<?php

namespace Labstag\Security\Voter;

use Labstag\Entity\Category;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CategoryVoter extends Voter
{
    protected function supports($attribute, $subject): bool
    {
        unset($attribute);

        return $subject instanceof Category;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        unset($attribute, $subject, $token);

        return true;
    }
}
