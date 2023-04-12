<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TagRepository::class)]
class Tag {

    /* ------------------------------------------------- */
    /* ------------------- VARIABLES ------------------- */
    /* ------------------------------------------------- */

    /**
     * @var ?int L'Identifiant du tag
     */    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var ?string Le titre du tag
     */
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    /* ----------------------------------------------- */
    /* ------------------- GETTERS ------------------- */
    /* ----------------------------------------------- */

    /** 
     * Retourne l'Identifiant associé au tag.
     * 
     * @return ?int Un entier représentant l'identifiant du tag en question.
     */
    public function getId(): ?int { return $this->id; }

    /** 
     * Retourne le titre associé au tag.
     * 
     * @return ?string Une chaîne de caractère stockant le titre du tag
     */
    public function getTitle(): ?string { return $this->title; }

    /* ----------------------------------------------- */
    /* ------------------- SETTERS ------------------- */
    /* ----------------------------------------------- */

    /**
     * Définit le titre du tag.
     * 
     * @param string Le titre en question à donné au tag.
     * 
     * @return self Le tag en question.
     */
    public function setTitle(string $title): self {

        $this->title = $title;
        return $this;
    }
}
