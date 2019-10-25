<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EnterpriseRepository")
 */
class Enterprise
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nameEnterprise;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameEnterprise(): ?string
    {
        return $this->nameEnterprise;
    }

    public function setNameEnterprise(string $nameEnterprise): self
    {
        $this->nameEnterprise = $nameEnterprise;

        return $this;
    }
}
