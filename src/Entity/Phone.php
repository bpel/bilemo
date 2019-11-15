<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Hateoas\Configuration\Annotation as Hateoas;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhoneRepository")
 * @Hateoas\Relation("self", href = "expr('/api/phones/' ~ object.getId())")
 */
class Phone
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200, unique=true)
     * @SWG\Property(type="string", maxLength=200)
     */
    private $namePhone;

    /**
     * @ORM\ManyToOne(targetEntity="Brand", cascade={"persist"})
     * @JoinColumn(name="brand", referencedColumnName="id")
     * @SWG\Property(ref=@Model(type=Brand::class))
     */
    private $brand;

    /**
     * @ORM\Column(type="string", length=100)
     * @SWG\Property(type="string", maxLength=255)
     */
    private $colour;

    /**
     * @ORM\ManyToOne(targetEntity="OsPhoneVersion", cascade={"persist"})
     * @JoinColumn(name="osversion", referencedColumnName="id")
     * @SWG\Property(ref=@Model(type=OsPhoneVersion::class))
     */
    private $osVersion;

    /**
     * @ORM\Column(type="integer")
     */
    private $goStorage;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNamephone(): ?string
    {
        return $this->namePhone;
    }

    public function setNamephone(string $namePhone): self
    {
        $this->namePhone = $namePhone;

        return $this;
    }

    public function getColour(): ?string
    {
        return $this->colour;
    }

    public function setColour(string $colour): self
    {
        $this->colour = $colour;

        return $this;
    }

    public function getGoStorage(): ?int
    {
        return $this->goStorage;
    }

    public function setGoStorage(int $goStorage): self
    {
        $this->goStorage = $goStorage;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    public function setBrand(?Brand $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getOsVersion(): ?OsPhoneVersion
    {
        return $this->osVersion;
    }

    public function setOsVersion(?OsPhoneVersion $osVersion): self
    {
        $this->osVersion = $osVersion;

        return $this;
    }


}
