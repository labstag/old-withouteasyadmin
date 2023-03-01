<?php

namespace Labstag\Entity;

use Doctrine\ORM\Mapping as ORM;
use Labstag\Repository\WorkflowGroupeRepository;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

#[ORM\Entity(repositoryClass: WorkflowGroupeRepository::class)]
class WorkflowGroupe
{

    #[ORM\ManyToOne(targetEntity: Groupe::class, inversedBy: 'workflowGroupes', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'refgroupe_id')]
    private ?Groupe $refgroupe = null;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'guid', unique: true)]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private $id;

    #[ORM\Column(type: 'boolean')]
    private ?bool $state = null;

    #[ORM\ManyToOne(targetEntity: Workflow::class, inversedBy: 'workflowGroupes', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'refworkflow_id')]
    private ?Workflow $refworkflow = null;

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
