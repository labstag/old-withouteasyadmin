<?php

namespace Labstag\Entity;

use Doctrine\ORM\Mapping as ORM;
use Labstag\Repository\WorkflowGroupeRepository;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

/**
 * @ORM\Entity(repositoryClass=WorkflowGroupeRepository::class)
 */
class WorkflowGroupe
{

    /**
     * @ORM\Id
     *
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\Column(type="guid", unique=true)
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    protected string $id;

    /**
     * @ORM\ManyToOne(targetEntity=Groupe::class, inversedBy="workflowGroupes", cascade={"persist"})
     */
    private ?Groupe $refgroupe = null;

    /**
     * @ORM\ManyToOne(targetEntity=Workflow::class, inversedBy="workflowGroupes", cascade={"persist"})
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

    public function getRefgroupe(): ?Groupe
    {
        return $this->refgroupe;
    }

    public function getRefworkflow(): ?Workflow
    {
        return $this->refworkflow;
    }

    public function getState(): ?bool
    {
        return $this->state;
    }

    public function setRefgroupe(?Groupe $groupe): self
    {
        $this->refgroupe = $groupe;

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
