<?php

namespace Labstag\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({"user": "AdresseUser"})
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 */
abstract class Adresse
{
    use SoftDeleteableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid", unique=true)
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    protected $rue;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     * @Assert\Country
     */
    protected $country;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    protected $zipcode;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank
     */
    protected $ville;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $gps;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank
     */
    protected $type;

    /** @ORM\Column(type="boolean") */
    protected $pmr;

    public function __toString()
    {
        return implode(
            ' ',
            [
                $this->getRue(),
                $this->getZipcode(),
                $this->getVille(),
                $this->getCountry(),
            ]
        );
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setRue(string $rue): self
    {
        $this->rue = $rue;

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

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getGps(): ?string
    {
        return $this->gps;
    }

    public function setGps(string $gps): self
    {
        $this->gps = $gps;

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

    public function isPmr(): ?bool
    {
        return $this->pmr;
    }

    public function setPmr(bool $pmr): self
    {
        $this->pmr = $pmr;

        return $this;
    }
}
