<?php

namespace Labstag\Security\Voter;

use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FieldDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\MenuItemDto;
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
use Labstag\Entity\Layout;
use Labstag\Entity\Libelle;
use Labstag\Entity\LinkUser;
use Labstag\Entity\Memo;
use Labstag\Entity\Menu;
use Labstag\Entity\Page;
use Labstag\Entity\PhoneUser;
use Labstag\Entity\Post;
use Labstag\Entity\Render;
use Labstag\Entity\Route;
use Labstag\Entity\Template;
use Labstag\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class EntityVoter extends Voter
{
    /**
     * @var int
     */
    final public const NBR_CHAPTER = 2;

    protected function canMoveHistory(History $history, TokenInterface $token): bool
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
        $entities = [
            AddressUser::class,
            Attachment::class,
            Block::class,
            Bookmark::class,
            Category::class,
            Chapter::class,
            Configuration::class,
            Edito::class,
            EmailUser::class,
            GeoCode::class,
            Groupe::class,
            History::class,
            Layout::class,
            Libelle::class,
            LinkUser::class,
            Memo::class,
            Menu::class,
            Page::class,
            PhoneUser::class,
            Post::class,
            Render::class,
            Route::class,
            Template::class,
            User::class,
        ];

        $status = false;

        if ($subject instanceof EntityDto || $subject instanceof FieldDto || $subject instanceof MenuItemDto) {
            return true;
        }

        if (is_array($subject) || is_null($subject)) {
            return true;
        }

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
        if ($subject instanceof History) {
            $state = match ($attribute) {
                'move'  => $this->canMoveHistory($subject, $token),
                default => true,
            };
        }

        return $state;
    }
}
