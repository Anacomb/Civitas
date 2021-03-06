<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProblemeRepository")
 */
class Probleme
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string")
     */
    private $localisation;

    /**
     * @ORM\Column(type="string")
     */
    private $reference;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Commune", inversedBy="Problemes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Commune;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Categorie", inversedBy="Problemes")
     */
    private $Categorie;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Priorite", inversedBy="Problemes")
     */
    private $Priorite;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\HistoriqueStatut", mappedBy="Probleme")
     */
    private $HistoriqueStatuts;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="Probleme")
     */
    private $Images;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Intervenir", mappedBy="Probleme", orphanRemoval=true)
     */
    private $Intervenirs;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\CompteRendu", mappedBy="Probleme")
     */
    private $CompteRendus;

    public function __construct()
    {
        $this->HistoriqueStatuts = new ArrayCollection();
        $this->Images = new ArrayCollection();
        $this->Intervenirs = new ArrayCollection();
        $this->CompteRendus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLocalisation(): ?string
    {
        return $this->localisation;
    }

    public function setLocalisation(string $localisation): self
    {
        $this->localisation = $localisation;

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getCommune(): ?Commune
    {
        return $this->Commune;
    }

    public function setCommune(?Commune $Commune): self
    {
        $this->Commune = $Commune;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->Categorie;
    }

    public function setCategorie(?Categorie $Categorie): self
    {
        $this->Categorie = $Categorie;

        return $this;
    }

    public function getPriorite(): ?Priorite
    {
        return $this->Priorite;
    }

    public function setPriorite(?Priorite $Priorite): self
    {
        $this->Priorite = $Priorite;

        return $this;
    }

    /**
     * @return Collection|HistoriqueStatut[]
     */
    public function getHistoriqueStatuts(): Collection
    {
        return $this->HistoriqueStatuts;
    }

    public function addHistoriqueStatut(HistoriqueStatut $historiqueStatut): self
    {
        if (!$this->HistoriqueStatuts->contains($historiqueStatut)) {
            $this->HistoriqueStatuts[] = $historiqueStatut;
            $historiqueStatut->setProbleme($this);
        }

        return $this;
    }

    public function removeHistoriqueStatut(HistoriqueStatut $historiqueStatut): self
    {
        if ($this->HistoriqueStatuts->contains($historiqueStatut)) {
            $this->HistoriqueStatuts->removeElement($historiqueStatut);
            // set the owning side to null (unless already changed)
            if ($historiqueStatut->getProbleme() === $this) {
                $historiqueStatut->setProbleme(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->Images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->Images->contains($image)) {
            $this->Images[] = $image;
            $image->setProbleme($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->Images->contains($image)) {
            $this->Images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getProbleme() === $this) {
                $image->setProbleme(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Intervenir[]
     */
    public function getIntervenirs(): Collection
    {
        return $this->Intervenirs;
    }

    public function addIntervenir(Intervenir $intervenir): self
    {
        if (!$this->Intervenirs->contains($intervenir)) {
            $this->Intervenirs[] = $intervenir;
            $intervenir->setProbleme($this);
        }

        return $this;
    }

    public function removeIntervenir(Intervenir $intervenir): self
    {
        if ($this->Intervenirs->contains($intervenir)) {
            $this->Intervenirs->removeElement($intervenir);
            // set the owning side to null (unless already changed)
            if ($intervenir->getProbleme() === $this) {
                $intervenir->setProbleme(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CompteRendu[]
     */
    public function getCompteRendus(): Collection
    {
        return $this->CompteRendus;
    }

    public function addCompteRendus(CompteRendu $compteRendus): self
    {
        if (!$this->CompteRendus->contains($compteRendus)) {
            $this->CompteRendus[] = $compteRendus;
            $compteRendus->setProbleme($this);
        }

        return $this;
    }

    public function removeCompteRendus(CompteRendu $compteRendus): self
    {
        if ($this->CompteRendus->contains($compteRendus)) {
            $this->CompteRendus->removeElement($compteRendus);
            // set the owning side to null (unless already changed)
            if ($compteRendus->getProbleme() === $this) {
                $compteRendus->setProbleme(null);
            }
        }

        return $this;
    }

    public function getLabel(): ?string
    {
        return $this->titre."(".$this->getReference().")";
    }


    public function __toString()
    {
        return $this->getTitre();
    }

}
