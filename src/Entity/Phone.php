<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PhoneRepository")
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
     */
    private $namephone;

    /**
     * @ORM\ManyToOne(targetEntity="Brand", cascade={"persist"})
     * @JoinColumn(name="brand", referencedColumnName="id")
     */
    private $brand;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $colour;

    /**
     * @ORM\ManyToOne(targetEntity="OsPhone", cascade={"persist"})
     * @JoinColumn(name="os", referencedColumnName="id")
     */
    private $os;

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
        return $this->namephone;
    }

    public function setNamephone(string $namephone): self
    {
        $this->namephone = $namephone;

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

    public function getGoStorage(): ?float
    {
        return $this->goStorage;
    }

    public function setGoStorage(float $goStorage): self
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

    public function getOs(): ?OsPhone
    {
        return $this->os;
    }

    public function setOs(?OsPhone $os): self
    {
        $this->os = $os;

        return $this;
    }
    
}
