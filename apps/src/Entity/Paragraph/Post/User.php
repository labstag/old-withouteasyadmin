<?php

namespace Labstag\Entity\Paragraph\Post;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Labstag\Entity\Paragraph;
use Labstag\Interfaces\ParagraphInterface;
use Labstag\Repository\Paragraph\Post\UserRepository;
use Stringable;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'paragraph_post_user')]
#[ApiResource(routePrefix: '/paragraph/post')]
class User implements Stringable, ParagraphInterface
{

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'guid', unique: true)]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private $id;

    #[ORM\ManyToOne(targetEntity: Paragraph::class, inversedBy: 'postUsers', cascade: ['persist'])]
    private ?Paragraph $paragraph = null;

    public function __toString(): string
    {
        return (string) $this->getParagraph()->getType();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getParagraph(): ?Paragraph
    {
        return $this->paragraph;
    }

    public function setParagraph(?Paragraph $paragraph): self
    {
        $this->paragraph = $paragraph;

        return $this;
    }
}
