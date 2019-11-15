<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EnterpriseRepository")
 * @Hateoas\Relation("self", href = "expr('/api/enterprises/' ~ object.getId())")
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
     * @SWG\Property(type="string", maxLength=255)
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
