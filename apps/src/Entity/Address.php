<?php

namespace Labstag\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Labstag\Interfaces\EntityTrashInterface;
use Stringable;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Validator\Constraints as Assert;

#[Gedmo\SoftDeleteable(fieldName: 'deletedAt', timeAware: false)]
#[ORM\Entity]
#[ORM\InheritanceType('SINGLE_TABLE')]
#[ORM\DiscriminatorColumn(name: 'discr', type: 'string')]
#[ORM\DiscriminatorMap(['user' => 'AddressUser'])]
abstract class Address implements Stringable, EntityTrashInterface
{
    use SoftDeleteableEntity;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    protected string $city;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    #[Assert\Country]
    protected string $country;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    protected string $gps;

    #[ORM\Column(type: 'boolean')]
    protected bool $pmr;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    protected string $street;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\NotBlank]
    protected string $type;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    protected string $zipcode;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'guid', unique: true)]
    #[ORM\CustomIdGenerator(class: UuidGenerator::class)]
    private ?string $id = null;

    public function __toString(): string
    {
        return implode(
            ' ',
            [
                $this->getStreet(),
                $this->getZipcode(),
                $this->getCity(),
                $this->getCountry(),
            ]
        );
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function getGps(): ?string
    {
        return $this->gps;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getPmr(): ?bool
    {
        return $this->pmr;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function isPmr(): ?bool
    {
        return $this->pmr;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function setGps(string $gps): self
    {
        $this->gps = $gps;

        return $this;
    }

    public function setPmr(bool $pmr): self
    {
        $this->pmr = $pmr;

        return $this;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function setZipcode(string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }
}
