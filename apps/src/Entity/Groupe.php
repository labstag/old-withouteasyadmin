<?php

namespace Labstag\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Labstag\Repository\GroupeRepository;
use Stringable;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=GroupeRepository::class)
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
class Groupe implements Stringable
{
    use SoftDeleteableEntity;

    /**
     * @Gedmo\Slug(updatable=false, fields={"name"})
     * @ORM\Column(type="string", length=255, unique=true)
     */
    protected $code;

    /**
     * @ORM\Id
     *
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\Column(type="guid", unique=true)
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    protected string $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank
     */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity=RouteGroupe::class, mappedBy="refgroupe", cascade={"persist"}, orphanRemoval=true)
     */
    protected $routes;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="refgroupe", cascade={"persist"}, orphanRemoval=true)
     */
    protected $users;

    /**
     * @ORM\OneToMany(targetEntity=WorkflowGroupe::class, mappedBy="refgroupe", cascade={"persist"}, orphanRemoval=true)
     */
    private $workflowGroupes;

    public function __construct()
    {
        $this->routes = new ArrayCollection();
        $this->workflowGroupes = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function __toString(): string
    {
        return (string) $this->getName();
    }

    public function addRoute(RouteGroupe $routeGroupe): self
    {
        if (!$this->routes->contains($routeGroupe)) {
            $this->routes[] = $routeGroupe;
            $routeGroupe->setRefgroupe($this);
        }

        return $this;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setRefgroupe($this);
        }

        return $this;
    }

    public function addWorkflowGroupe(WorkflowGroupe $workflowGroupe): self
    {
        if (!$this->workflowGroupes->contains($workflowGroupe)) {
            $this->workflowGroupes[] = $workflowGroupe;
            $workflowGroupe->setRefgroupe($this);
        }

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getRoutes(): Collection
    {
        return $this->routes;
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function getWorkflowGroupes(): Collection
    {
        return $this->workflowGroupes;
    }

    public function removeRoute(RouteGroupe $routeGroupe): self
    {
        $this->removeElementGroupe($this->routes, $routeGroupe);

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getRefgroupe() === $this) {
                $user->setRefgroupe(null);
            }
        }

        return $this;
    }

    public function removeWorkflowGroupe(WorkflowGroupe $workflowGroupe): self
    {
        $this->removeElementGroupe($this->workflowGroupes, $workflowGroupe);

        return $this;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    private function removeElementGroupe($element, $variable)
    {
        if ($element->removeElement($variable) && $variable->getRefgroupe() === $this) {
            $variable->setRefgroupe(null);
        }
    }
}
