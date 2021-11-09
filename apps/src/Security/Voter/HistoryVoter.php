<?php

namespace Labstag\Security\Voter;

use Labstag\Entity\History;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class HistoryVoter extends Voter
{
    public const NBR_CHAPTER = 2;

    protected function canMove(History $entity, TokenInterface $token): bool
    {
        unset($token);
        $chapters = $entity->getChapters();

        return count($chapters) >= self::NBR_CHAPTER;
    }

    protected function supports($attribute, $subject)
    {
        unset($attribute);

        return $subject instanceof History;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        switch ($attribute) {
            case 'move':
                $return = $this->canMove($subject, $token);

                break;
            default:
                $return = true;
        }

        return $return;
    }
}
