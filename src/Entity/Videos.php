<?php

namespace App\Entity;

use App\Repository\VideosRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=VideosRepository::class)
 */
class Videos
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Cet champ est obligatoire")
     * @Assert\Length(
     *      min=2,
     *      max=250,
     *      minMessage="Cet champs doit contenir minimum {{ limit }} caractères",
     *      maxMessage="Cet champs ne peut pas dépasser {{ limit }} caractères"
     * )
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity=Tricks::class, inversedBy="videos", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $trick;

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

    public function getTrick(): ?Tricks
    {
        return $this->trick;
    }

    public function setTrick(?Tricks $trick): self
    {
        $this->trick = $trick;

        return $this;
    }
}
