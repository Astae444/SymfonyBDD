<?php

namespace App\Entity;

use App\Repository\ProprietaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProprietaireRepository::class)
 */
class Proprietaire
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\ManyToMany(targetEntity=Chaton::class, mappedBy="proprietaire_id")
     */
    private $chaton_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\ManyToMany(targetEntity=Chaton::class, mappedBy="proprietaire_id")
     */
    private $chatons;

    public function __construct()
    {
        $this->chaton_id = new ArrayCollection();
        $this->chatons = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, Chaton>
     */
    public function getChatonId(): Collection
    {
        return $this->chaton_id;
    }

    public function addChatonId(Chaton $chatonId): self
    {
        if (!$this->chaton_id->contains($chatonId)) {
            $this->chaton_id[] = $chatonId;
            $chatonId->addProprietaireId($this);
        }

        return $this;
    }

    public function removeChatonId(Chaton $chatonId): self
    {
        if ($this->chaton_id->removeElement($chatonId)) {
            $chatonId->removeProprietaireId($this);
        }

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * @return Collection<int, Chaton>
     */
    public function getChatons(): Collection
    {
        return $this->chatons;
    }

    public function addChaton(Chaton $chaton): self
    {
        if (!$this->chatons->contains($chaton)) {
            $this->chatons[] = $chaton;
            $chaton->addProprietaireId($this);
        }

        return $this;
    }

    public function removeChaton(Chaton $chaton): self
    {
        if ($this->chatons->removeElement($chaton)) {
            $chaton->removeProprietaireId($this);
        }

        return $this;
    }
}