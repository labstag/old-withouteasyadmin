<?php

namespace Labstag\Lib;

use Labstag\Repository\AddressUserRepository;
use Labstag\Repository\AttachmentRepository;
use Labstag\Repository\BlockRepository;
use Labstag\Repository\BookmarkRepository;
use Labstag\Repository\CategoryRepository;
use Labstag\Repository\ChapterRepository;
use Labstag\Repository\EditoRepository;
use Labstag\Repository\EmailUserRepository;
use Labstag\Repository\GeoCodeRepository;
use Labstag\Repository\GroupeRepository;
use Labstag\Repository\HistoryRepository;
use Labstag\Repository\LayoutRepository;
use Labstag\Repository\LibelleRepository;
use Labstag\Repository\LinkUserRepository;
use Labstag\Repository\MemoRepository;
use Labstag\Repository\MenuRepository;
use Labstag\Repository\PageRepository;
use Labstag\Repository\PhoneUserRepository;
use Labstag\Repository\PostRepository;
use Labstag\Repository\RenderRepository;
use Labstag\Repository\TemplateRepository;
use Labstag\Repository\UserRepository;
use Labstag\Search\AttachmentSearch;
use Labstag\Search\BlockSearch;
use Labstag\Search\BookmarkSearch;
use Labstag\Search\CategorySearch;
use Labstag\Search\ChapterSearch;
use Labstag\Search\EditoSearch;
use Labstag\Search\GeoCodeSearch;
use Labstag\Search\GroupeSearch;
use Labstag\Search\HistorySearch;
use Labstag\Search\LayoutSearch;
use Labstag\Search\LibelleSearch;
use Labstag\Search\MemoSearch;
use Labstag\Search\MenuSearch;
use Labstag\Search\PageSearch;
use Labstag\Search\PostSearch;
use Labstag\Search\ProfilSearch;
use Labstag\Search\RenderSearch;
use Labstag\Search\TemplateSearch;
use Labstag\Search\User\AddressUserSearch;
use Labstag\Search\User\EmailUserSearch;
use Labstag\Search\User\LinkUserSearch;
use Labstag\Search\User\PhoneUserSearch;
use Labstag\Search\UserSearch;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class DomainLib
{
    public function __construct(
        protected UserSearch $userSearch,
        protected PhoneUserRepository $phoneUserRepository,
        protected PhoneUserSearch $phoneUserSearch,
        protected LinkUserRepository $linkUserRepository,
        protected LinkUserSearch $linkUserSearch,
        protected GroupeRepository $groupeRepository,
        protected GroupeSearch $groupeSearch,
        protected EmailUserRepository $emailUserRepository,
        protected EmailUserSearch $emailUserSearch,
        protected AddressUserRepository $addressUserRepository,
        protected AddressUserSearch $addressUserSearch,
        protected HistoryRepository $historyRepository,
        protected HistorySearch $historySearch,
        protected ChapterRepository $chapterRepository,
        protected ChapterSearch $chapterSearch,
        protected TemplateRepository $templateRepository,
        protected TemplateSearch $templateSearch,
        protected RenderRepository $renderRepository,
        protected RenderSearch $renderSearch,
        protected UserRepository $userRepository,
        protected ProfilSearch $profilSearch,
        protected PostRepository $postRepository,
        protected PostSearch $postSearch,
        protected PageRepository $pageRepository,
        protected PageSearch $pageSearch,
        protected MenuRepository $menuRepository,
        protected MenuSearch $menuSearch,
        protected MemoRepository $memoRepository,
        protected MemoSearch $memoSearch,
        protected LibelleRepository $libelleRepository,
        protected LibelleSearch $libelleSearch,
        protected LayoutRepository $layoutRepository,
        protected LayoutSearch $layoutSearch,
        protected GeoCodeRepository $geoCodeRepository,
        protected GeoCodeSearch $geoCodeSearch,
        protected EditoRepository $editoRepository,
        protected EditoSearch $editoSearch,
        protected CategoryRepository $categoryRepository,
        protected CategorySearch $categorySearch,
        protected BookmarkRepository $bookmarkRepository,
        protected BookmarkSearch $bookmarkSearch,
        protected BlockRepository $blockRepository,
        protected BlockSearch $blockSearch,
        protected AttachmentRepository $attachmentRepository,
        protected AttachmentSearch $attachmentSearch,
        protected TranslatorInterface $translator
    )
    {
    }

    public function getSearchForm(): string
    {
        return '';
    }

    public function getUrlAdmin(): array
    {
        return [];
    }
}
