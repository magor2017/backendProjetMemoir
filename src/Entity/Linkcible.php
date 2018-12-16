<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LinkcibleRepository")
 */
class Linkcible
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $campagne;

    /**
     * @ORM\Column(type="integer")
     */
    private $formulaire;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $niveau;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $classe;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCampagne(): ?int
    {
        return $this->campagne;
    }

    public function setCampagne(int $campagne): self
    {
        $this->campagne = $campagne;

        return $this;
    }

    public function getFormulaire(): ?int
    {
        return $this->formulaire;
    }

    public function setFormulaire(int $formulaire): self
    {
        $this->formulaire = $formulaire;

        return $this;
    }

    public function getNiveau(): ?string
    {
        return $this->niveau;
    }

    public function setNiveau(string $niveau): self
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function getClasse(): ?string
    {
        return $this->classe;
    }

    public function setClasse(string $classe): self
    {
        $this->classe = $classe;

        return $this;
    }
}
