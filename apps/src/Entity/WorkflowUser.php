<?php

namespace Labstag\Entity;

use Doctrine\ORM\Mapping as ORM;
use Labstag\Repository\WorkflowUserRepository;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

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
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="workflowUsers", cascade={"persist"})
     */
    private $refuser;

    /**
     * @ORM\ManyToOne(targetEntity=Workflow::class, inversedBy="workflowUsers", cascade={"persist"})
     */
    private $refworkflow;

    /**
     * @ORM\Column(type="boolean")
     */
    private $state;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getRefuser(): ?User
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

    public function setRefuser(?User $user): self
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
