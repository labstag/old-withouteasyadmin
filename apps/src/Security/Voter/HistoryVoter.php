<?php

namespace Labstag\Security\Voter;

use Labstag\Entity\History;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class HistoryVoter extends Voter
{
    /**
     * @var int
     */
    final public const NBR_CHAPTER = 2;

    protected function move(History $history, TokenInterface $token): bool
    {
        unset($token);
        $chapters = $history->getChapters();

        return count($chapters) >= self::NBR_CHAPTER;
    }

    protected function supports(
        mixed $attribute,
        mixed $subject
    ): bool
    {
        unset($attribute);

        return $subject::class == History::class;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $state = true;
        if ($subject instanceof History) {
            $state = match ($attribute) {
                'move'  => $this->move($subject, $token),
                default => true,
            };
        }

        return $state;
    }
}
