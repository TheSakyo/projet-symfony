<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment {
    
    /* ------------------------------------------------- */
    /* ------------------- VARIABLES ------------------- */
    /* ------------------------------------------------- */

    /**
     * @var ?int L'Identifiant du commentaire
     */ 
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]   
    private ?int $id = null;

    /**
     * @var ?string Le contenu du commentaire
     */    
    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    /**
     * @var ?\DateTimeImmutable La dâte du commentaire
     */
    #[ORM\Column]
    private ?\DateTimeImmutable $date = null;

    
    /**
     * @var ?User L'Utilisateur associé au commentaire
     */
    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /**
     * @var ?Article L'Article associé au commentaire
     */
    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Article $article = null;

    /* ----------------------------------------------- */
    /* ------------------- GETTERS ------------------- */
    /* ----------------------------------------------- */

    /** 
     * Retourne l'Identifiant associé au commentaire.
     * 
     * @return ?int Un entier représentant l'identifiant du commentaire en question.
     */
    public function getId(): ?int { return $this->id; }

    /** 
     * Retourne le contenu associé au commentaire.
     * 
     * @return ?int Un entier représentant le contenu du commentaire en question.
     */
    public function getContent(): ?string { return $this->content; }

    /** 
     * Retourne la dâte associé au commentaire.
     * 
     * @return ?\DateTimeImmutable La dâte associé au commentaire
     */
    public function getDate(): ?\DateTimeImmutable { return $this->date; }


    /** 
     * Retourne l'utilisateur associé au commentaire.
     * 
     * @return ?User L'Utilisateur associé au commentaire
     */
    public function getUser(): ?User { return $this->user; }

    /** 
     * Retourne l'article associé au commentaire.
     * 
     * @return ?User L'Article associé au commentaire
     */
    public function getArticle(): ?Article { return $this->article; }


    /* ----------------------------------------------- */
    /* ------------------- SETTERS ------------------- */
    /* ----------------------------------------------- */

    /**
     * Définit le contenu du commentaire.
     * 
     * @param string Le contenu en question à donné au commentaire.
     * 
     * @return self Le commentaire en question.
     */
    public function setContent(string $content): self {

        $this->content = $content;
        return $this;
    }

    /**
     * Définit la dâte du commentaire.
     * 
     * @param \DateTimeImmutable La dâte en question à donnée au commentaire.
     * 
     * @return self Le commentaire en question.
     */   
    public function setDate(\DateTimeImmutable $date): self {

        $this->date = $date;
        return $this;
    }

    /**
     * Définit l'Utilisateur du commentaire.
     * 
     * @param ?User l'Utilisateur en question à donné au commentaire.
     * 
     * @return self Le commentaire en question.
     */
    public function setUser(?User $user): self {
        $this->user = $user;
        return $this;
    }

    /**
     * Définit l'Article du commentaire.
     * 
     * @param ?Article l'Article en question à donné au commentaire.
     * 
     * @return self Le commentaire en question.
     */
    public function setArticle(?Article $article): self {

        $this->article = $article;
        return $this;
    }

    /* ------------------------------------------------ */
    /* ------------------- MÉTHODES ------------------- */
    /* ------------------------------------------------ */

    public function __toString() { 
        
        return "Commentaire N°".strval($this->getId()); 
    }
}
