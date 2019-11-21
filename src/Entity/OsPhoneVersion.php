<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OsPhoneVersionRepository")
 * @Hateoas\Relation("self", href = "expr('/api/osversions/' ~ object.getId())")
 */
class OsPhoneVersion
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @SWG\Property(type="string", maxLength=100)
     */
    private $version;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\OsPhone")
     * @SWG\Property(ref=@Model(type=OsPhone::class))
     */
    private $os;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(string $version): self
    {
        $this->version = $version;

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
