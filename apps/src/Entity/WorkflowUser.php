<?php

namespace Labstag\Entity;

use Doctrine\ORM\Mapping as ORM;
use Labstag\Repository\WorkflowUserRepository;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=WorkflowUserRepository::class)
 */
class WorkflowUser
{

    /**
     * @ORM\Id
     *
     * @ORM\GeneratedValue(strategy="CUSTOM")
     *
     * @ORM\Column(type="guid", unique=true)
     *
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    protected string $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="workflowUsers", cascade={"persist"})
     */
    private ?UserInterface $refuser = null;

    /**
     * @ORM\ManyToOne(targetEntity=Workflow::class, inversedBy="workflowUsers", cascade={"persist"})
     */
    private ?Workflow $refworkflow = null;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $state = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getRefuser(): ?UserInterface
    {
        return $this->refuser;
    }

    public function getRefworkflow(): ?Workflow
    {
        return $this->refworkflow;
    }

    public function getState(): ?bool
    {
        return $this->state;
    }

    public function setRefuser(?UserInterface $user): self
    {
        $this->refuser = $user;

        return $this;
    }

    public function setRefworkflow(?Workflow $workflow): self
    {
        $this->refworkflow = $workflow;

        return $this;
    }

    public function setState(bool $state): self
    {
        $this->state = $state;

        return $this;
    }
}
