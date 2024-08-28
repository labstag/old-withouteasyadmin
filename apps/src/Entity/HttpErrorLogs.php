<?php

namespace Labstag\Entity;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Labstag\Interfaces\EntityInterface;
use Labstag\Repository\HttpErrorLogsRepository;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: HttpErrorLogsRepository::class)]
class HttpErrorLogs implements EntityInterface
{

    #[ORM\Column(length: 255)]
    private ?string $agent = null;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(type: 'datetime')]
    private DateTime $dateTime;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $domain = null;

    #[ORM\Column]
    private ?int $httpCode = null;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'guid', unique: true)]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    private ?string $internetProtocol = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $referer = null;

    #[ORM\Column]
    private array $requestData = [];

    #[ORM\Column(length: 255)]
    private ?string $requestMethod = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $url = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'httpErrorLogs', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'refuser_id', nullable: false)]
    private ?UserInterface $user = null;

    public function getAgent(): ?string
    {
        return $this->agent;
    }

    public function getCreated()
    {
        return $this->dateTime;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function getHttpCode(): ?int
    {
        return $this->httpCode;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getInternetProtocol(): ?string
    {
        return $this->internetProtocol;
    }

    public function getReferer(): ?string
    {
        return $this->referer;
    }

    public function getRequestData(): array
    {
        return $this->requestData;
    }

    public function getRequestMethod(): ?string
    {
        return $this->requestMethod;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setAgent(string $agent): static
    {
        $this->agent = $agent;

        return $this;
    }

    public function setDomain(string $domain): static
    {
        $this->domain = $domain;

        return $this;
    }

    public function setHttpCode(int $httpCode): static
    {
        $this->httpCode = $httpCode;

        return $this;
    }

    public function setInternetProtocol(string $internetProtocol): self
    {
        $this->internetProtocol = $internetProtocol;

        return $this;
    }

    public function setReferer(?string $referer): static
    {
        $this->referer = $referer;

        return $this;
    }

    public function setRequestData(array $requestData): static
    {
        $this->requestData = $requestData;

        return $this;
    }

    public function setRequestMethod(string $requestMethod): static
    {
        $this->requestMethod = $requestMethod;

        return $this;
    }

    public function setUrl(string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function setUser(?UserInterface $user): static
    {
        $this->user = $user;

        return $this;
    }
}
