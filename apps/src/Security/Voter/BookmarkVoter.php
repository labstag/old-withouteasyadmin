<?php

namespace Labstag\Security\Voter;

use Labstag\Entity\Bookmark;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class BookmarkVoter extends Voter
{
    protected function supports($attribute, $subject): bool
    {
        unset($attribute);

        return $subject instanceof Bookmark;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        unset($attribute, $subject, $token);

        return true;
    }
}
