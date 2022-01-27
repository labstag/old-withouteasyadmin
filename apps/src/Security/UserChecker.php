<?php

namespace Labstag\Security;

use Labstag\Entity\User;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        $state = (array) $user->getState();
        if (in_array('disable', $state)) {
            throw new AccountExpiredException('Your account is disabled.');
        }

        if (in_array('nonverifier', $state)) {
            throw new AccountExpiredException('Your account is not validated.');
        }
    }

    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }
    }
}
