<?php

namespace Labstag\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Labstag\Entity\Paragraph\Bookmark as ParagraphBookmark;
use Labstag\Entity\Paragraph\Bookmark\Category as BookmarkCategory;
use Labstag\Entity\Paragraph\Bookmark\Libelle as BookmarkLibelle;
use Labstag\Entity\Paragraph\Bookmark\Liste as BookmarkList;
use Labstag\Entity\Paragraph\Edito as ParagraphEdito;
use Labstag\Entity\Paragraph\History as ParagraphHistory;
use Labstag\Entity\Paragraph\History\Chapter as HistoryChapter;
use Labstag\Entity\Paragraph\History\Liste as HistoryList;
use Labstag\Entity\Paragraph\History\Show as HistoryShow;
use Labstag\Entity\Paragraph\History\User as HistoryUser;
use Labstag\Entity\Paragraph\Post\Archive as PostArchive;
use Labstag\Entity\Paragraph\Post as ParagraphPost;
use Labstag\Entity\Paragraph\Post\Category as PostCategory;
use Labstag\Entity\Paragraph\Post\Header as PostHeader;
use Labstag\Entity\Paragraph\Post\Libelle as PostLibelle;
use Labstag\Entity\Paragraph\Post\Liste as PostList;
use Labstag\Entity\Paragraph\Post\Show as PostShow;
use Labstag\Entity\Paragraph\Post\User as PostUser;
use Labstag\Entity\Paragraph\Post\Year as PostYear;
use Labstag\Entity\Paragraph\Text;
use Labstag\Repository\ParagraphRepository;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

/**
 * @ORM\Entity(repositoryClass=ParagraphRepository::class)
 */
class Paragraph
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\Column(type="guid", unique=true)
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $background;

    /**
     * @ORM\OneToMany(targetEntity=BookmarkCategory::class, mappedBy="paragraph", orphanRemoval=true)
     */
    private $bookmarkCategories;

    /**
     * @ORM\OneToMany(targetEntity=BookmarkLibelle::class, mappedBy="paragraph", orphanRemoval=true)
     */
    private $bookmarkLibelles;

    /**
     * @ORM\OneToMany(targetEntity=BookmarkList::class, mappedBy="paragraph", orphanRemoval=true)
     */
    private $bookmarkLists;

    /**
     * @ORM\OneToMany(targetEntity=ParagraphBookmark::class, mappedBy="paragraph", orphanRemoval=true)
     */
    private $bookmarks;

    /**
     * @ORM\ManyToOne(targetEntity=Chapter::class, inversedBy="paragraphs")
     */
    private $chapter;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $color;

    /**
     * @ORM\ManyToOne(targetEntity=Edito::class, inversedBy="paragraphs")
     */
    private $edito;

    /**
     * @ORM\OneToMany(targetEntity=ParagraphEdito::class, mappedBy="paragraph", orphanRemoval=true)
     */
    private $editos;

    /**
     * @ORM\OneToMany(targetEntity=ParagraphHistory::class, mappedBy="paragraph", orphanRemoval=true)
     */
    private $histories;

    /**
     * @ORM\ManyToOne(targetEntity=History::class, inversedBy="paragraphs")
     */
    private $history;

    /**
     * @ORM\OneToMany(targetEntity=HistoryChapter::class, mappedBy="paragraph", orphanRemoval=true)
     */
    private $historyChapters;

    /**
     * @ORM\OneToMany(targetEntity=HistoryList::class, mappedBy="paragraph", orphanRemoval=true)
     */
    private $historyLists;

    /**
     * @ORM\OneToMany(targetEntity=HistoryShow::class, mappedBy="paragraph")
     */
    private $historyShows;

    /**
     * @ORM\OneToMany(targetEntity=HistoryUser::class, mappedBy="paragraph", orphanRemoval=true)
     */
    private $historyUsers;

    /**
     * @ORM\ManyToOne(targetEntity=Layout::class, inversedBy="paragraphs")
     */
    private $layout;

    /**
     * @ORM\ManyToOne(targetEntity=Memo::class, inversedBy="paragraphs")
     */
    private $memo;

    /**
     * @ORM\ManyToOne(targetEntity=Page::class, inversedBy="paragraphs")
     */
    private $page;

    /**
     * @ORM\Column(type="integer")
     */
    private $position;

    /**
     * @ORM\ManyToOne(targetEntity=Post::class, inversedBy="paragraphs")
     */
    private $post;

    /**
     * @ORM\OneToMany(targetEntity=PostArchive::class, mappedBy="paragraph", orphanRemoval=true)
     */
    private $postArchives;

    /**
     * @ORM\OneToMany(targetEntity=PostCategory::class, mappedBy="paragraph", orphanRemoval=true)
     */
    private $postCategories;

    /**
     * @ORM\OneToMany(targetEntity=PostHeader::class, mappedBy="paragraph", orphanRemoval=true)
     */
    private $postHeaders;

    /**
     * @ORM\OneToMany(targetEntity=PostLibelle::class, mappedBy="paragraph", orphanRemoval=true)
     */
    private $postLibelles;

    /**
     * @ORM\OneToMany(targetEntity=PostList::class, mappedBy="paragraph", orphanRemoval=true)
     */
    private $postLists;

    /**
     * @ORM\OneToMany(targetEntity=ParagraphPost::class, mappedBy="paragraph", orphanRemoval=true)
     */
    private $posts;

    /**
     * @ORM\OneToMany(targetEntity=PostShow::class, mappedBy="paragraph", orphanRemoval=true)
     */
    private $postShows;

    /**
     * @ORM\OneToMany(targetEntity=PostUser::class, mappedBy="paragraph", orphanRemoval=true)
     */
    private $postUsers;

    /**
     * @ORM\OneToMany(targetEntity=PostYear::class, mappedBy="paragraph", orphanRemoval=true)
     */
    private $postYears;

    /**
     * @ORM\OneToMany(targetEntity=Text::class, mappedBy="paragraph", orphanRemoval=true)
     */
    private $texts;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    public function __construct()
    {
        $this->position           = 0;
        $this->texts              = new ArrayCollection();
        $this->bookmarks          = new ArrayCollection();
        $this->histories          = new ArrayCollection();
        $this->editos             = new ArrayCollection();
        $this->posts              = new ArrayCollection();
        $this->postLists          = new ArrayCollection();
        $this->historyLists       = new ArrayCollection();
        $this->bookmarkLists      = new ArrayCollection();
        $this->postArchives       = new ArrayCollection();
        $this->postUsers          = new ArrayCollection();
        $this->postLibelles       = new ArrayCollection();
        $this->bookmarkLibelles   = new ArrayCollection();
        $this->bookmarkCategories = new ArrayCollection();
        $this->postYears          = new ArrayCollection();
        $this->postShows          = new ArrayCollection();
        $this->postCategories     = new ArrayCollection();
        $this->postHeaders        = new ArrayCollection();
        $this->historyUsers       = new ArrayCollection();
        $this->historyChapters    = new ArrayCollection();
        $this->historyShows       = new ArrayCollection();
    }

    public function addBookmark(ParagraphBookmark $bookmark): self
    {
        if (!$this->bookmarks->contains($bookmark)) {
            $this->bookmarks[] = $bookmark;
            $bookmark->setParagraph($this);
        }

        return $this;
    }

    public function addBookmarkCategory(BookmarkCategory $bookmarkCategory): self
    {
        if (!$this->bookmarkCategories->contains($bookmarkCategory)) {
            $this->bookmarkCategories[] = $bookmarkCategory;
            $bookmarkCategory->setParagraph($this);
        }

        return $this;
    }

    public function addBookmarkLibelle(BookmarkLibelle $bookmarkLibelle): self
    {
        if (!$this->bookmarkLibelles->contains($bookmarkLibelle)) {
            $this->bookmarkLibelles[] = $bookmarkLibelle;
            $bookmarkLibelle->setParagraph($this);
        }

        return $this;
    }

    public function addBookmarkList(BookmarkList $bookmarkList): self
    {
        if (!$this->bookmarkLists->contains($bookmarkList)) {
            $this->bookmarkLists[] = $bookmarkList;
            $bookmarkList->setParagraph($this);
        }

        return $this;
    }

    public function addEdito(ParagraphEdito $edito): self
    {
        if (!$this->editos->contains($edito)) {
            $this->editos[] = $edito;
            $edito->setParagraph($this);
        }

        return $this;
    }

    public function addHistory(ParagraphHistory $history): self
    {
        if (!$this->histories->contains($history)) {
            $this->histories[] = $history;
            $history->setParagraph($this);
        }

        return $this;
    }

    public function addHistoryChapter(HistoryChapter $historyChapter): self
    {
        if (!$this->historyChapters->contains($historyChapter)) {
            $this->historyChapters[] = $historyChapter;
            $historyChapter->setParagraph($this);
        }

        return $this;
    }

    public function addHistoryList(HistoryList $historyList): self
    {
        if (!$this->historyLists->contains($historyList)) {
            $this->historyLists[] = $historyList;
            $historyList->setParagraph($this);
        }

        return $this;
    }

    public function addHistoryShow(HistoryShow $historyShow): self
    {
        if (!$this->historyShows->contains($historyShow)) {
            $this->historyShows[] = $historyShow;
            $historyShow->setParagraph($this);
        }

        return $this;
    }

    public function addHistoryUser(HistoryUser $historyUser): self
    {
        if (!$this->historyUsers->contains($historyUser)) {
            $this->historyUsers[] = $historyUser;
            $historyUser->setParagraph($this);
        }

        return $this;
    }

    public function addPost(ParagraphPost $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setParagraph($this);
        }

        return $this;
    }

    public function addPostArchive(PostArchive $postArchive): self
    {
        if (!$this->postArchives->contains($postArchive)) {
            $this->postArchives[] = $postArchive;
            $postArchive->setParagraph($this);
        }

        return $this;
    }

    public function addPostCategory(PostCategory $postCategory): self
    {
        if (!$this->postCategories->contains($postCategory)) {
            $this->postCategories[] = $postCategory;
            $postCategory->setParagraph($this);
        }

        return $this;
    }

    public function addPostHeader(PostHeader $postHeader): self
    {
        if (!$this->postHeaders->contains($postHeader)) {
            $this->postHeaders[] = $postHeader;
            $postHeader->setParagraph($this);
        }

        return $this;
    }

    public function addPostLibelle(PostLibelle $postLibelle): self
    {
        if (!$this->postLibelles->contains($postLibelle)) {
            $this->postLibelles[] = $postLibelle;
            $postLibelle->setParagraph($this);
        }

        return $this;
    }

    public function addPostList(PostList $postList): self
    {
        if (!$this->postLists->contains($postList)) {
            $this->postLists[] = $postList;
            $postList->setParagraph($this);
        }

        return $this;
    }

    public function addPostShow(PostShow $postShow): self
    {
        if (!$this->postShows->contains($postShow)) {
            $this->postShows[] = $postShow;
            $postShow->setParagraph($this);
        }

        return $this;
    }

    public function addPostUser(PostUser $postUser): self
    {
        if (!$this->postUsers->contains($postUser)) {
            $this->postUsers[] = $postUser;
            $postUser->setParagraph($this);
        }

        return $this;
    }

    public function addPostYear(PostYear $postYear): self
    {
        if (!$this->postYears->contains($postYear)) {
            $this->postYears[] = $postYear;
            $postYear->setParagraph($this);
        }

        return $this;
    }

    public function addText(Text $text): self
    {
        if (!$this->texts->contains($text)) {
            $this->texts[] = $text;
            $text->setParagraph($this);
        }

        return $this;
    }

    public function getBackground(): ?string
    {
        return $this->background;
    }

    /**
     * @return Collection<int, BookmarkCategory>
     */
    public function getBookmarkCategories(): Collection
    {
        return $this->bookmarkCategories;
    }

    /**
     * @return Collection<int, BookmarkLibelle>
     */
    public function getBookmarkLibelles(): Collection
    {
        return $this->bookmarkLibelles;
    }

    /**
     * @return Collection<int, BookmarkList>
     */
    public function getBookmarkLists(): Collection
    {
        return $this->bookmarkLists;
    }

    /**
     * @return Collection<int, Bookmark>
     */
    public function getBookmarks(): Collection
    {
        return $this->bookmarks;
    }

    public function getChapter(): ?Chapter
    {
        return $this->chapter;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function getEdito(): ?Edito
    {
        return $this->edito;
    }

    /**
     * @return Collection<int, Edito>
     */
    public function getEditos(): Collection
    {
        return $this->editos;
    }

    /**
     * @return Collection<int, History>
     */
    public function getHistories(): Collection
    {
        return $this->histories;
    }

    public function getHistory(): ?History
    {
        return $this->history;
    }

    /**
     * @return Collection<int, HistoryChapter>
     */
    public function getHistoryChapters(): Collection
    {
        return $this->historyChapters;
    }

    /**
     * @return Collection<int, HistoryList>
     */
    public function getHistoryLists(): Collection
    {
        return $this->historyLists;
    }

    /**
     * @return Collection<int, HistoryShow>
     */
    public function getHistoryShows(): Collection
    {
        return $this->historyShows;
    }

    /**
     * @return Collection<int, HistoryUser>
     */
    public function getHistoryUsers(): Collection
    {
        return $this->historyUsers;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getLayout(): ?Layout
    {
        return $this->layout;
    }

    public function getMemo(): ?Memo
    {
        return $this->memo;
    }

    public function getPage(): ?Page
    {
        return $this->page;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    /**
     * @return Collection<int, PostArchive>
     */
    public function getPostArchives(): Collection
    {
        return $this->postArchives;
    }

    /**
     * @return Collection<int, PostCategory>
     */
    public function getPostCategories(): Collection
    {
        return $this->postCategories;
    }

    /**
     * @return Collection<int, PostHeader>
     */
    public function getPostHeaders(): Collection
    {
        return $this->postHeaders;
    }

    /**
     * @return Collection<int, PostLibelle>
     */
    public function getPostLibelles(): Collection
    {
        return $this->postLibelles;
    }

    /**
     * @return Collection<int, PostList>
     */
    public function getPostLists(): Collection
    {
        return $this->postLists;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    /**
     * @return Collection<int, PostShow>
     */
    public function getPostShows(): Collection
    {
        return $this->postShows;
    }

    /**
     * @return Collection<int, PostUser>
     */
    public function getPostUsers(): Collection
    {
        return $this->postUsers;
    }

    /**
     * @return Collection<int, PostYear>
     */
    public function getPostYears(): Collection
    {
        return $this->postYears;
    }

    /**
     * @return Collection<int, Text>
     */
    public function getTexts(): Collection
    {
        return $this->texts;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function removeBookmark(ParagraphBookmark $bookmark): self
    {
        if ($this->bookmarks->removeElement($bookmark)) {
            // set the owning side to null (unless already changed)
            if ($bookmark->getParagraph() === $this) {
                $bookmark->setParagraph(null);
            }
        }

        return $this;
    }

    public function removeBookmarkCategory(BookmarkCategory $bookmarkCategory): self
    {
        if ($this->bookmarkCategories->removeElement($bookmarkCategory)) {
            // set the owning side to null (unless already changed)
            if ($bookmarkCategory->getParagraph() === $this) {
                $bookmarkCategory->setParagraph(null);
            }
        }

        return $this;
    }

    public function removeBookmarkLibelle(BookmarkLibelle $bookmarkLibelle): self
    {
        if ($this->bookmarkLibelles->removeElement($bookmarkLibelle)) {
            // set the owning side to null (unless already changed)
            if ($bookmarkLibelle->getParagraph() === $this) {
                $bookmarkLibelle->setParagraph(null);
            }
        }

        return $this;
    }

    public function removeBookmarkList(BookmarkList $bookmarkList): self
    {
        if ($this->bookmarkLists->removeElement($bookmarkList)) {
            // set the owning side to null (unless already changed)
            if ($bookmarkList->getParagraph() === $this) {
                $bookmarkList->setParagraph(null);
            }
        }

        return $this;
    }

    public function removeEdito(ParagraphEdito $edito): self
    {
        if ($this->editos->removeElement($edito)) {
            // set the owning side to null (unless already changed)
            if ($edito->getParagraph() === $this) {
                $edito->setParagraph(null);
            }
        }

        return $this;
    }

    public function removeHistory(ParagraphHistory $history): self
    {
        if ($this->histories->removeElement($history)) {
            // set the owning side to null (unless already changed)
            if ($history->getParagraph() === $this) {
                $history->setParagraph(null);
            }
        }

        return $this;
    }

    public function removeHistoryChapter(HistoryChapter $historyChapter): self
    {
        if ($this->historyChapters->removeElement($historyChapter)) {
            // set the owning side to null (unless already changed)
            if ($historyChapter->getParagraph() === $this) {
                $historyChapter->setParagraph(null);
            }
        }

        return $this;
    }

    public function removeHistoryList(HistoryList $historyList): self
    {
        if ($this->historyLists->removeElement($historyList)) {
            // set the owning side to null (unless already changed)
            if ($historyList->getParagraph() === $this) {
                $historyList->setParagraph(null);
            }
        }

        return $this;
    }

    public function removeHistoryShow(HistoryShow $historyShow): self
    {
        if ($this->historyShows->removeElement($historyShow)) {
            // set the owning side to null (unless already changed)
            if ($historyShow->getParagraph() === $this) {
                $historyShow->setParagraph(null);
            }
        }

        return $this;
    }

    public function removeHistoryUser(HistoryUser $historyUser): self
    {
        if ($this->historyUsers->removeElement($historyUser)) {
            // set the owning side to null (unless already changed)
            if ($historyUser->getParagraph() === $this) {
                $historyUser->setParagraph(null);
            }
        }

        return $this;
    }

    public function removePost(ParagraphPost $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getParagraph() === $this) {
                $post->setParagraph(null);
            }
        }

        return $this;
    }

    public function removePostArchive(PostArchive $postArchive): self
    {
        if ($this->postArchives->removeElement($postArchive)) {
            // set the owning side to null (unless already changed)
            if ($postArchive->getParagraph() === $this) {
                $postArchive->setParagraph(null);
            }
        }

        return $this;
    }

    public function removePostCategory(PostCategory $postCategory): self
    {
        if ($this->postCategories->removeElement($postCategory)) {
            // set the owning side to null (unless already changed)
            if ($postCategory->getParagraph() === $this) {
                $postCategory->setParagraph(null);
            }
        }

        return $this;
    }

    public function removePostHeader(PostHeader $postHeader): self
    {
        if ($this->postHeaders->removeElement($postHeader)) {
            // set the owning side to null (unless already changed)
            if ($postHeader->getParagraph() === $this) {
                $postHeader->setParagraph(null);
            }
        }

        return $this;
    }

    public function removePostLibelle(PostLibelle $postLibelle): self
    {
        if ($this->postLibelles->removeElement($postLibelle)) {
            // set the owning side to null (unless already changed)
            if ($postLibelle->getParagraph() === $this) {
                $postLibelle->setParagraph(null);
            }
        }

        return $this;
    }

    public function removePostList(PostList $postList): self
    {
        if ($this->postLists->removeElement($postList)) {
            // set the owning side to null (unless already changed)
            if ($postList->getParagraph() === $this) {
                $postList->setParagraph(null);
            }
        }

        return $this;
    }

    public function removePostShow(PostShow $postShow): self
    {
        if ($this->postShows->removeElement($postShow)) {
            // set the owning side to null (unless already changed)
            if ($postShow->getParagraph() === $this) {
                $postShow->setParagraph(null);
            }
        }

        return $this;
    }

    public function removePostUser(PostUser $postUser): self
    {
        if ($this->postUsers->removeElement($postUser)) {
            // set the owning side to null (unless already changed)
            if ($postUser->getParagraph() === $this) {
                $postUser->setParagraph(null);
            }
        }

        return $this;
    }

    public function removePostYear(PostYear $postYear): self
    {
        if ($this->postYears->removeElement($postYear)) {
            // set the owning side to null (unless already changed)
            if ($postYear->getParagraph() === $this) {
                $postYear->setParagraph(null);
            }
        }

        return $this;
    }

    public function removeText(Text $text): self
    {
        if ($this->texts->removeElement($text)) {
            // set the owning side to null (unless already changed)
            if ($text->getParagraph() === $this) {
                $text->setParagraph(null);
            }
        }

        return $this;
    }

    public function setBackground(?string $background): self
    {
        $this->background = $background;

        return $this;
    }

    public function setChapter(?Chapter $chapter): self
    {
        $this->chapter = $chapter;

        return $this;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function setEdito(?Edito $edito): self
    {
        $this->edito = $edito;

        return $this;
    }

    public function setHistory(?History $history): self
    {
        $this->history = $history;

        return $this;
    }

    public function setLayout(?Layout $layout): self
    {
        $this->layout = $layout;

        return $this;
    }

    public function setMemo(?Memo $memo): self
    {
        $this->memo = $memo;

        return $this;
    }

    public function setPage(?Page $page): self
    {
        $this->page = $page;

        return $this;
    }

    public function setPosition(int $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }
}
