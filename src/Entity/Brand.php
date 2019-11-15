<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BrandRepository")
 * @Hateoas\Relation("self", href = "expr('/api/brands/' ~ object.getId())")
 */
class Brand
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
    private $nameBrand;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameBrand(): ?string
    {
        return $this->nameBrand;
    }

    public function setNameBrand(string $nameBrand): self
    {
        $this->nameBrand = $nameBrand;

        return $this;
    }

}
