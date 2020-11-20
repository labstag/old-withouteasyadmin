<?php

namespace Labstag\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Labstag\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Labstag\Entity\Traits\VerifEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface
{
    use VerifEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid", unique=true)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", nullable=true)
     *
     * @var string
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var string|null
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enable;

    /**
     * @ORM\Column(type="boolean")
     */
    private $lost;

    /**
     * @ORM\ManyToOne(targetEntity=Groupe::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=true)
     */
    private $groupe;

    /**
     * @ORM\OneToMany(targetEntity=Edito::class, mappedBy="refuser")
     */
    private $editos;

    /**
     * @ORM\OneToMany(targetEntity=NoteInterne::class, mappedBy="refuser")
     */
    private $noteInternes;

    /**
     * @ORM\OneToMany(targetEntity=LienUser::class, mappedBy="refuser")
     */
    private $lienUsers;

    /**
     * @ORM\OneToMany(targetEntity=EmailUser::class, mappedBy="refuser")
     */
    private $emailUsers;

    /**
     * @ORM\OneToMany(targetEntity=PhoneUser::class, mappedBy="refuser")
     */
    private $phoneUsers;

    /**
     * @ORM\OneToMany(targetEntity=AdresseUser::class, mappedBy="refuser")
     */
    private $adresseUsers;

    /**
     * @ORM\OneToMany(targetEntity=OauthConnectUser::class, mappedBy="refuser")
     *
     * @var ArrayCollection
     */
    private $oauthConnectUsers;

    public function __construct()
    {
        $this->roles             = ['ROLE_USER'];
        $this->enable            = false;
        $this->lost              = false;
        $this->verif             = false;
        $this->editos            = new ArrayCollection();
        $this->noteInternes      = new ArrayCollection();
        $this->oauthConnectUsers = new ArrayCollection();
        $this->lienUsers         = new ArrayCollection();
        $this->emailUsers        = new ArrayCollection();
        $this->phoneUsers        = new ArrayCollection();
        $this->adresseUsers      = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getUsername();
    }

    /**
     * @return string|null
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->setPassword('');
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getId(): ?string
    {
        return $this->id;
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

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
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

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt(): string
    {
        return '';
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isEnable(): ?bool
    {
        return $this->enable;
    }

    public function setEnable(bool $enable): self
    {
        $this->enable = $enable;

        return $this;
    }

    public function getGroupe(): ?Groupe
    {
        return $this->groupe;
    }

    public function setGroupe(?Groupe $groupe): self
    {
        $this->groupe = $groupe;

        return $this;
    }

    public function getEditos(): Collection
    {
        return $this->editos;
    }

    public function addEdito(Edito $edito): self
    {
        if (!$this->editos->contains($edito)) {
            $this->editos[] = $edito;
            $edito->setRefuser($this);
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

    public function getNoteInternes(): Collection
    {
        return $this->noteInternes;
    }

    public function addNoteInterne(NoteInterne $noteInterne): self
    {
        if (!$this->noteInternes->contains($noteInterne)) {
            $this->noteInternes[] = $noteInterne;
            $noteInterne->setRefuser($this);
        }

        return $this;
    }

    public function removeNoteInterne(NoteInterne $noteInterne): self
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

    public function getLienUsers(): Collection
    {
        return $this->lienUsers;
    }

    public function addLienUser(LienUser $lienUser): self
    {
        if (!$this->lienUsers->contains($lienUser)) {
            $this->lienUsers[] = $lienUser;
            $lienUser->setRefuser($this);
        }

        return $this;
    }

    public function removeLienUser(LienUser $lienUser): self
    {
        if ($this->lienUsers->contains($lienUser)) {
            $this->lienUsers->removeElement($lienUser);
            // set the owning side to null (unless already changed)
            if ($lienUser->getRefuser() === $this) {
                $lienUser->setRefuser(null);
            }
        }

        return $this;
    }

    public function getEmailUsers(): Collection
    {
        return $this->emailUsers;
    }

    public function addEmailUser(EmailUser $emailUser): self
    {
        if (!$this->emailUsers->contains($emailUser)) {
            $this->emailUsers[] = $emailUser;
            $emailUser->setRefuser($this);
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

    public function getPhoneUsers(): Collection
    {
        return $this->phoneUsers;
    }

    public function addPhoneUser(PhoneUser $phoneUser): self
    {
        if (!$this->phoneUsers->contains($phoneUser)) {
            $this->phoneUsers[] = $phoneUser;
            $phoneUser->setRefuser($this);
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

    public function getAdresseUsers(): Collection
    {
        return $this->adresseUsers;
    }

    public function addAdresseUser(AdresseUser $adresseUser): self
    {
        if (!$this->adresseUsers->contains($adresseUser)) {
            $this->adresseUsers[] = $adresseUser;
            $adresseUser->setRefuser($this);
        }

        return $this;
    }

    public function removeAdresseUser(AdresseUser $adresseUser): self
    {
        if ($this->adresseUsers->contains($adresseUser)) {
            $this->adresseUsers->removeElement($adresseUser);
            // set the owning side to null (unless already changed)
            if ($adresseUser->getRefuser() === $this) {
                $adresseUser->setRefuser(null);
            }
        }

        return $this;
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

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function isLost(): ?bool
    {
        return $this->lost;
    }

    public function setLost(bool $lost): self
    {
        $this->lost = $lost;

        return $this;
    }

    public function getOauthConnectUsers(): Collection
    {
        return $this->oauthConnectUsers;
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
}
