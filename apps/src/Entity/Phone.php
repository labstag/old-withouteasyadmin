<?php
namespace Labstag\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"user": "PhoneUser"})
 */
abstract class Phone
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid", unique=true)
     */
    private $id;

    /** @ORM\Column(type="string", length=255) */
    private $numero;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @Assert\Country
     */
    private $country;

    /** @ORM\Column(type="string", length=255) */
    private $type;

    /** @ORM\Column(type="boolean") */
    private $principal;

    public function __toString()
    {
        return implode(
            ' ',
            [
                $this->getCountry(),
                $this->getNumero(),
            ]
        );
    }

    public function __construct()
    {
        $this->principal = false;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function isPrincipal(): ?bool
    {
        return $this->principal;
    }

    public function setPrincipal(bool $principal): self
    {
        $this->principal = $principal;

        return $this;
    }
}
