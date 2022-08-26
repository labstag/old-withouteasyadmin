<?php

namespace Labstag\Security\Voter;

use Labstag\Entity\AddressUser;
use Labstag\Entity\Attachment;
use Labstag\Entity\Block;
use Labstag\Entity\Bookmark;
use Labstag\Entity\Category;
use Labstag\Entity\Chapter;
use Labstag\Entity\Configuration;
use Labstag\Entity\Edito;
use Labstag\Entity\EmailUser;
use Labstag\Entity\GeoCode;
use Labstag\Entity\Groupe;
use Labstag\Entity\History;
use Labstag\Entity\Libelle;
use Labstag\Entity\LinkUser;
use Labstag\Entity\Memo;
use Labstag\Entity\Menu;
use Labstag\Entity\Page;
use Labstag\Entity\PhoneUser;
use Labstag\Entity\Post;
use Labstag\Entity\Route;
use Labstag\Entity\Template;
use Labstag\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class EntityVoter extends Voter
{
    final public const NBR_CHAPTER = 2;

    protected function canEditEdito(Edito $entity, TokenInterface $token): bool
    {
        unset($token);
        $state = $entity->getState();

        return !(in_array($state, ['publie', 'rejete']));
    }

    protected function canEditMemo(Memo $entity, TokenInterface $token): bool
    {
        unset($token);
        $state = $entity->getState();

        return !(in_array($state, ['publie', 'rejete']));
    }

    protected function canMoveHistory(History $entity, TokenInterface $token): bool
    {
        unset($token);
        $chapters = $entity->getChapters();

        return count($chapters) >= self::NBR_CHAPTER;
    }

    protected function supports($attribute, $subject): bool
    {
        unset($attribute);
        $entities = [
            AddressUser::class,
            Attachment::class,
            Bookmark::class,
            Block::class,
            Category::class,
            Chapter::class,
            Configuration::class,
            Edito::class,
            EmailUser::class,
            GeoCode::class,
            Groupe::class,
            History::class,
            Libelle::class,
            LinkUser::class,
            Memo::class,
            Menu::class,
            Page::class,
            PhoneUser::class,
            Post::class,
            Route::class,
            Template::class,
            User::class,
        ];

        $status = false;
        foreach ($entities as $entity) {
            if ($subject::class == $entity) {
                $status = true;

                break;
            }
        }

        return $status;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $state = true;
        if ($subject instanceof Edito) {
            $state = match ($attribute) {
                'edit' => $this->canEditEdito($subject, $token),
                default => true,
            };
        } elseif ($subject instanceof History) {
            $state = match ($attribute) {
                'move' => $this->canMoveHistory($subject, $token),
                default => true,
            };
        } elseif ($subject instanceof Memo) {
            $state = match ($attribute) {
                'edit' => $this->canEditMemo($subject, $token),
                default => true,
            };
        }

        return $state;
    }
}
