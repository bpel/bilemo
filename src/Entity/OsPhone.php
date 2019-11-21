<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OsPhoneRepository")
 * @Hateoas\Relation("self", href = "expr('/api/os/' ~ object.getId())")
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
     * @SWG\Property(type="string", maxLength=100)
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
