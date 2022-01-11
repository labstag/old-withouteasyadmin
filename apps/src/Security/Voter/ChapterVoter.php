<?php

namespace Labstag\Security\Voter;

use Labstag\Entity\Chapter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ChapterVoter extends Voter
{
    protected function supports($attribute, $subject): bool
    {
        unset($attribute);

        return $subject instanceof Chapter;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        unset($attribute, $subject, $token);

        return true;
    }
}
