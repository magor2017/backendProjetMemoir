<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReponseRepository")
 */
class Reponse
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
    private $formulaire;

    /**
     * @ORM\Column(type="text")
     */
    private $reponse;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getReponse(): ?string
    {
        return $this->reponse;
    }

    public function setReponse(string $reponse): self
    {
        $this->reponse = $reponse;

        return $this;
    }
}
