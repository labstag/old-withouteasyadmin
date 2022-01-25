<?php

namespace Labstag\Security\Voter;

use Labstag\Entity\AddressUser;
use Labstag\Entity\Attachment;
use Labstag\Entity\Bookmark;
use Labstag\Entity\Category;
use Labstag\Entity\Chapter;
use Labstag\Entity\Configuration;
use Labstag\Entity\EmailUser;
use Labstag\Entity\GeoCode;
use Labstag\Entity\Groupe;
use Labstag\Entity\Layout;
use Labstag\Entity\Libelle;
use Labstag\Entity\LinkUser;
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
    protected function supports($attribute, $subject): bool
    {
        unset($attribute);
        $entities = [
            AddressUser::class,
            Attachment::class,
            Bookmark::class,
            Category::class,
            Chapter::class,
            Configuration::class,
            EmailUser::class,
            GeoCode::class,
            Groupe::class,
            Layout::class,
            Libelle::class,
            LinkUser::class,
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
            if ($subject::class== $entity) {
                $status = true;
                break;
            }
        }

        return $status;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        unset($attribute, $subject, $token);

        return true;
    }
}
