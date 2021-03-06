<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompteRenduRepository")
 */
class CompteRendu
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $urlDocument;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Personne", inversedBy="CompteRendus")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Personne;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Probleme", inversedBy="CompteRendus")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Probleme;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Intervenir", inversedBy="CompteRendus")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Intervenir;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titre;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrlDocument(): ?string
    {
        return $this->urlDocument;
    }

    public function setUrlDocument(string $urlDocument): self
    {
        $this->urlDocument = $urlDocument;

        return $this;
    }

    public function getPersonne(): ?Personne
    {
        return $this->Personne;
    }

    public function setPersonne(?Personne $Personne): self
    {
        $this->Personne = $Personne;

        return $this;
    }

    public function getProbleme(): ?Probleme
    {
        return $this->Probleme;
    }

    public function setProbleme(?Probleme $Probleme): self
    {
        $this->Probleme = $Probleme;

        return $this;
    }

    public function __toString()
    {
        return $this->getUrlDocument();
    }

    public function getIntervenir(): ?Intervenir
    {
        return $this->Intervenir;
    }

    public function setIntervenir(?Intervenir $Intervenir): self
    {
        $this->Intervenir = $Intervenir;

        return $this;
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

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }


}
