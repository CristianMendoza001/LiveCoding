<?php

namespace App\Entity;

use App\Repository\PruebitaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PruebitaRepository::class)]
class Pruebita
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Texto = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTexto(): ?string
    {
        return $this->Texto;
    }

    public function setTexto(string $Texto): self
    {
        $this->Texto = $Texto;

        return $this;
    }
}
