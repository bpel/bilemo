<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OsPhoneRepository")
 */
class OsPhone
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     */
    private $nameOs;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameOs(): ?string
    {
        return $this->nameOs;
    }

    public function setNameOs(string $nameOs): self
    {
        $this->nameOs = $nameOs;

        return $this;
    }
}
