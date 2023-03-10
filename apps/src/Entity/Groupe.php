<?php

namespace Labstag\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Labstag\Interfaces\EntityTrashInterface;
use Labstag\Repository\GroupeRepository;
use Stringable;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Validator\Constraints as Assert;

#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false)]
#[ORM\Entity(repositoryClass: GroupeRepository::class)]
#[ApiResource]
class Groupe implements Stringable, EntityTrashInterface
{
    use SoftDeleteableEntity;

    #[Gedmo\Slug(updatable: false, fields: ['name'])]
    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $code;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'guid', unique: true)]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?string $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private string $name;

    #[ORM\OneToMany(
        targetEntity: RouteGroupe::class,
        mappedBy: 'groupe',
        cascade: ['persist'],
        orphanRemoval: true
    )
    ]
    private Collection $routes;

    #[ORM\OneToMany(
        targetEntity: User::class,
        mappedBy: 'groupe',
        cascade: ['persist'],
        orphanRemoval: true
    )
    ]
    private Collection $users;

    #[ORM\OneToMany(
        targetEntity: WorkflowGroupe::class,
        mappedBy: 'groupe',
        cascade: ['persist'],
        orphanRemoval: true
    )
    ]
    private Collection $workflowGroupes;

    public function __construct()
    {
        $this->routes          = new ArrayCollection();
        $this->workflowGroupes = new ArrayCollection();
        $this->users           = new ArrayCollection();
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

    public function getUsers(): Collection
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

    private function removeElementGroupe(
        Collection $element,
        mixed $variable
    ): void
    {
        if ($element->removeElement($variable) && $variable->getRefgroupe() === $this) {
            $variable->setRefgroupe(null);
        }
    }
}
