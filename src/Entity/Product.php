<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *
     * @var int
     *
     * @Groups({"elastica"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     *
     * @Groups({"elastica"})
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     *
     * @var float
     *
     * @Groups({"elastica"})
     */
    private $price;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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
}
