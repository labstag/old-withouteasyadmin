<?php

namespace Labstag\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Labstag\Annotation\Uploadable;
use Labstag\Annotation\UploadableField;
use Labstag\Entity\Traits\StateableEntity;
use Labstag\Repository\UserRepository;
use Stringable;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @Uploadable
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface, Stringable
{
    use SoftDeleteableEntity;
    use StateableEntity;

    /**
     * @ORM\OneToMany(
     *     targetEntity=AddressUser::class,
     *     mappedBy="refuser",
     *     cascade={"persist"},
     *     orphanRemoval=true
     * )
     */
    protected $addressUsers;

    /**
     * @ORM\ManyToOne(targetEntity=Attachment::class, inversedBy="users")
     */
    protected $avatar;

    /**
     * @ORM\OneToMany(
     *     targetEntity=Edito::class,
     *     mappedBy="refuser",
     *     cascade={"persist"},
     *     orphanRemoval=true
     * )
     */
    protected $editos;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $email;

    /**
     * @ORM\OneToMany(
     *     targetEntity=EmailUser::class,
     *     mappedBy="refuser",
     *     cascade={"persist"},
     *     orphanRemoval=true
     * )
     */
    protected $emailUsers;

    /**
     * @UploadableField(filename="avatar", path="user/avatar", slug="username")
     */
    protected $file;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\Column(type="guid", unique=true)
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    protected $id;

    /**
     * @ORM\OneToMany(
     *     targetEntity=LinkUser::class,
     *     mappedBy="refuser",
     *     cascade={"persist"},
     *     orphanRemoval=true
     * )
     */
    protected $linkUsers;

    /**
     * @ORM\OneToMany(
     *     targetEntity=Memo::class,
     *     mappedBy="refuser",
     *     cascade={"persist"},
     *     orphanRemoval=true
     * )
     */
    protected $noteInternes;

    /**
     * @ORM\OneToMany(
     *     targetEntity=OauthConnectUser::class,
     *     mappedBy="refuser",
     *     cascade={"persist"},
     *     orphanRemoval=true
     * )
     */
    protected $oauthConnectUsers;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=true)
     */
    protected $password;

    /**
     * @ORM\OneToMany(
     *     targetEntity=PhoneUser::class,
     *     mappedBy="refuser",
     *     cascade={"persist"},
     *     orphanRemoval=true
     * )
     */
    protected $phoneUsers;

    /**
     * @var null|string
     */
    protected $plainPassword;

    /**
     * @ORM\ManyToOne(targetEntity=Groupe::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=true)
     */
    protected Groupe $refgroupe;

    /**
     * @ORM\Column(type="json")
     */
    protected array $roles = ['ROLE_USER'];

    /**
     * @ORM\OneToMany(targetEntity=RouteUser::class, mappedBy="refuser", orphanRemoval=true)
     */
    protected $routes;

    /**
     * @ORM\Column(type="string", length=180, unique=true, nullable=false)
     * @Assert\NotNull
     */
    protected $username;

    /**
     * @ORM\OneToMany(targetEntity=Bookmark::class, mappedBy="refuser", cascade={"persist"}, orphanRemoval=true)
     */
    private $bookmarks;

    /**
     * @ORM\OneToMany(targetEntity=History::class, mappedBy="refuser", orphanRemoval=true)
     */
    private $histories;

    /**
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="refuser", cascade={"persist"}, orphanRemoval=true)
     */
    private $posts;

    /**
     * @ORM\OneToMany(targetEntity=WorkflowUser::class, mappedBy="refuser", orphanRemoval=true)
     */
    private $workflowUsers;

    public function __construct()
    {
        $this->editos            = new ArrayCollection();
        $this->noteInternes      = new ArrayCollection();
        $this->linkUsers         = new ArrayCollection();
        $this->emailUsers        = new ArrayCollection();
        $this->phoneUsers        = new ArrayCollection();
        $this->addressUsers      = new ArrayCollection();
        $this->oauthConnectUsers = new ArrayCollection();
        $this->routes            = new ArrayCollection();
        $this->workflowUsers     = new ArrayCollection();
        $this->posts             = new ArrayCollection();
        $this->bookmarks         = new ArrayCollection();
        $this->histories         = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getUsername();
    }

    public function addAddressUser(AddressUser $addressUser): self
    {
        if (!$this->addressUsers->contains($addressUser)) {
            $this->addressUsers[] = $addressUser;
            $addressUser->setRefuser($this);
        }

        return $this;
    }

    public function addBookmark(Bookmark $bookmark): self
    {
        if (!$this->bookmarks->contains($bookmark)) {
            $this->bookmarks[] = $bookmark;
            $bookmark->setRefuser($this);
        }

        return $this;
    }

    public function addEdito(Edito $edito): self
    {
        if (!$this->editos->contains($edito)) {
            $this->editos[] = $edito;
            $edito->setRefuser($this);
        }

        return $this;
    }

    public function addEmailUser(EmailUser $emailUser): self
    {
        if (!$this->emailUsers->contains($emailUser)) {
            $emailUser->setRefuser($this);
            $this->emailUsers[] = $emailUser;
        }

        return $this;
    }

    public function addHistory(History $history): self
    {
        if (!$this->histories->contains($history)) {
            $this->histories[] = $history;
            $history->setRefuser($this);
        }

        return $this;
    }

    public function addLinkUser(LinkUser $linkUser): self
    {
        if (!$this->linkUsers->contains($linkUser)) {
            $linkUser->setRefuser($this);
            $this->linkUsers[] = $linkUser;
        }

        return $this;
    }

    public function addMemo(Memo $noteInterne): self
    {
        if (!$this->noteInternes->contains($noteInterne)) {
            $this->noteInternes[] = $noteInterne;
            $noteInterne->setRefuser($this);
        }

        return $this;
    }

    public function addNoteInterne(Memo $noteInterne): self
    {
        if (!$this->noteInternes->contains($noteInterne)) {
            $this->noteInternes[] = $noteInterne;
            $noteInterne->setRefuser($this);
        }

        return $this;
    }

    public function addOauthConnectUser(OauthConnectUser $oauthConnectUser): self
    {
        if (!$this->oauthConnectUsers->contains($oauthConnectUser)) {
            $this->oauthConnectUsers[] = $oauthConnectUser;
            $oauthConnectUser->setRefuser($this);
        }

        return $this;
    }

    public function addOauthConnectUsers(
        OauthConnectUser $oauthConnectUser
    ): self
    {
        if (!$this->oauthConnectUsers->contains($oauthConnectUser)) {
            $this->oauthConnectUsers[] = $oauthConnectUser;
            $oauthConnectUser->setRefuser($this);
        }

        return $this;
    }

    public function addPhoneUser(PhoneUser $phoneUser): self
    {
        if (!$this->phoneUsers->contains($phoneUser)) {
            $this->phoneUsers[] = $phoneUser;
            $phoneUser->setRefuser($this);
        }

        return $this;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setRefuser($this);
        }

        return $this;
    }

    public function addRoute(RouteUser $route): self
    {
        if (!$this->routes->contains($route)) {
            $this->routes[] = $route;
            $route->setRefuser($this);
        }

        return $this;
    }

    public function addWorkflowUser(WorkflowUser $workflowUser): self
    {
        if (!$this->workflowUsers->contains($workflowUser)) {
            $this->workflowUsers[] = $workflowUser;
            $workflowUser->setRefuser($this);
        }

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getAddressUsers()
    {
        return $this->addressUsers;
    }

    public function getAvatar(): ?Attachment
    {
        return $this->avatar;
    }

    public function getBookmarks(): Collection
    {
        return $this->bookmarks;
    }

    public function getEditos()
    {
        return $this->editos;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getEmail(): string
    {
        return (string) $this->email;
    }

    public function getEmailUsers()
    {
        return $this->emailUsers;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function getHistories(): Collection
    {
        return $this->histories;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getLinkUsers()
    {
        return $this->linkUsers;
    }

    public function getMemos()
    {
        return $this->noteInternes;
    }

    public function getNoteInternes(): Collection
    {
        return $this->noteInternes;
    }

    public function getOauthConnectUsers()
    {
        return $this->oauthConnectUsers;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function getPhoneUsers()
    {
        return $this->phoneUsers;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function getRefgroupe(): ?Groupe
    {
        return $this->refgroupe;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function getRoutes(): Collection
    {
        return $this->routes;
    }

    /**
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    public function getUserIdentifier(): string
    {
        return $this->getUsername();
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function getWorkflowUsers(): Collection
    {
        return $this->workflowUsers;
    }

    public function removeAddressUser(AddressUser $addressUser): self
    {
        if ($this->addressUsers->contains($addressUser)) {
            $this->addressUsers->removeElement($addressUser);
            // set the owning side to null (unless already changed)
            if ($addressUser->getRefuser() === $this) {
                $addressUser->setRefuser(null);
            }
        }

        return $this;
    }

    public function removeBookmark(Bookmark $bookmark): self
    {
        if ($this->bookmarks->removeElement($bookmark)) {
            // set the owning side to null (unless already changed)
            if ($bookmark->getRefuser() === $this) {
                $bookmark->setRefuser(null);
            }
        }

        return $this;
    }

    public function removeEdito(Edito $edito): self
    {
        if ($this->editos->contains($edito)) {
            $this->editos->removeElement($edito);
            // set the owning side to null (unless already changed)
            if ($edito->getRefuser() === $this) {
                $edito->setRefuser(null);
            }
        }

        return $this;
    }

    public function removeEmailUser(EmailUser $emailUser): self
    {
        if ($this->emailUsers->contains($emailUser)) {
            $this->emailUsers->removeElement($emailUser);
            // set the owning side to null (unless already changed)
            if ($emailUser->getRefuser() === $this) {
                $emailUser->setRefuser(null);
            }
        }

        return $this;
    }

    public function removeHistory(History $history): self
    {
        if ($this->histories->removeElement($history)) {
            // set the owning side to null (unless already changed)
            if ($history->getRefuser() === $this) {
                $history->setRefuser(null);
            }
        }

        return $this;
    }

    public function removeLinkUser(LinkUser $linkUser): self
    {
        if ($this->linkUsers->contains($linkUser)) {
            $this->linkUsers->removeElement($linkUser);
            // set the owning side to null (unless already changed)
            if ($linkUser->getRefuser() === $this) {
                $linkUser->setRefuser(null);
            }
        }

        return $this;
    }

    public function removeMemo(Memo $noteInterne): self
    {
        if ($this->noteInternes->contains($noteInterne)) {
            $this->noteInternes->removeElement($noteInterne);
            // set the owning side to null (unless already changed)
            if ($noteInterne->getRefuser() === $this) {
                $noteInterne->setRefuser(null);
            }
        }

        return $this;
    }

    public function removeNoteInterne(Memo $noteInterne): self
    {
        if ($this->noteInternes->removeElement($noteInterne)) {
            // set the owning side to null (unless already changed)
            if ($noteInterne->getRefuser() === $this) {
                $noteInterne->setRefuser(null);
            }
        }

        return $this;
    }

    public function removeOauthConnectUser(OauthConnectUser $oauthConnectUser): self
    {
        if ($this->oauthConnectUsers->removeElement($oauthConnectUser)) {
            // set the owning side to null (unless already changed)
            if ($oauthConnectUser->getRefuser() === $this) {
                $oauthConnectUser->setRefuser(null);
            }
        }

        return $this;
    }

    public function removeOauthConnectUsers(
        OauthConnectUser $oauthConnectUser
    ): self
    {
        if ($this->oauthConnectUsers->contains($oauthConnectUser)) {
            $this->oauthConnectUsers->removeElement($oauthConnectUser);
            // set the owning side to null (unless already changed)
            if ($oauthConnectUser->getRefuser() === $this) {
                $oauthConnectUser->setRefuser(null);
            }
        }

        return $this;
    }

    public function removePhoneUser(PhoneUser $phoneUser): self
    {
        if ($this->phoneUsers->contains($phoneUser)) {
            $this->phoneUsers->removeElement($phoneUser);
            // set the owning side to null (unless already changed)
            if ($phoneUser->getRefuser() === $this) {
                $phoneUser->setRefuser(null);
            }
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getRefuser() === $this) {
                $post->setRefuser(null);
            }
        }

        return $this;
    }

    public function removeRoute(RouteUser $route): self
    {
        if ($this->routes->removeElement($route)) {
            // set the owning side to null (unless already changed)
            if ($route->getRefuser() === $this) {
                $route->setRefuser(null);
            }
        }

        return $this;
    }

    public function removeWorkflowUser(WorkflowUser $workflowUser): self
    {
        if ($this->workflowUsers->removeElement($workflowUser)) {
            // set the owning side to null (unless already changed)
            if ($workflowUser->getRefuser() === $this) {
                $workflowUser->setRefuser(null);
            }
        }

        return $this;
    }

    public function serialize()
    {
        return serialize(
            [
                $this->id,
                $this->username,
                $this->password,
            ]
        );
    }

    public function setAvatar(?Attachment $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function setFile($file): self
    {
        $this->file = $file;

        return $this;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->setPassword('');
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function setRefgroupe(?Groupe $groupe): self
    {
        $this->refgroupe = $groupe;

        return $this;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function unserialize($serialized)
    {
        [
            $this->id,
            $this->username,
            $this->password,
        ] = unserialize($serialized);
    }
}
