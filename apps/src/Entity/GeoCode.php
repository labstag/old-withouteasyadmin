<?php

namespace Labstag\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Labstag\Repository\GeoCodeRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=GeoCodeRepository::class)
 */
class GeoCode
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid", unique=true)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=2)
     * @Assert\Country
     */
    private $countryCode;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $postalCode;

    /**
     * @ORM\Column(type="string", length=180)
     */
    private $placeName;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $stateName;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $stateCode;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $provinceName;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $provinceCode;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $communityName;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $communityCode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $latitude;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $longitude;

    /**
     * @ORM\Column(type="integer")
     */
    private $accuracy;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): self
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): self
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getPlaceName(): ?string
    {
        return $this->placeName;
    }

    public function setPlaceName(string $placeName): self
    {
        $this->placeName = $placeName;

        return $this;
    }

    public function getStateName(): ?string
    {
        return $this->stateName;
    }

    public function setStateName(string $stateName): self
    {
        $this->stateName = $stateName;

        return $this;
    }

    public function getStateCode(): ?string
    {
        return $this->stateCode;
    }

    public function setStateCode(string $stateCode): self
    {
        $this->stateCode = $stateCode;

        return $this;
    }

    public function getProvinceName(): ?string
    {
        return $this->provinceName;
    }

    public function setProvinceName(string $provinceName): self
    {
        $this->provinceName = $provinceName;

        return $this;
    }

    public function getProvinceCode(): ?string
    {
        return $this->provinceCode;
    }

    public function setProvinceCode(string $provinceCode): self
    {
        $this->provinceCode = $provinceCode;

        return $this;
    }

    public function getCommunityName(): ?string
    {
        return $this->communityName;
    }

    public function setCommunityName(string $communityName): self
    {
        $this->communityName = $communityName;

        return $this;
    }

    public function getCommunityCode(): ?string
    {
        return $this->communityCode;
    }

    public function setCommunityCode(string $communityCode): self
    {
        $this->communityCode = $communityCode;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getAccuracy(): ?int
    {
        return $this->accuracy;
    }

    public function setAccuracy($accuracy): self
    {
        $this->accuracy = $accuracy;

        return $this;
    }
}
