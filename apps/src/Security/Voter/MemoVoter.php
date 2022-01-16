<?php

namespace Labstag\Security\Voter;

use Labstag\Entity\Memo;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class MemoVoter extends Voter
{
    protected function canEdit(Memo $entity, TokenInterface $token): bool
    {
        unset($token);
        $state = $entity->getState();

        return !(in_array($state, ['publie', 'rejete']));
    }

    protected function supports($attribute, $subject): bool
    {
        unset($attribute);

        return $subject instanceof Memo;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $return = match ($attribute) {
            'edit' => $this->canEdit($subject, $token),
            default => true,
        };

        return $return;
    }
}
