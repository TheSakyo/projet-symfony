<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
class Article {

    /* ------------------------------------------------- */
    /* ------------------- VARIABLES ------------------- */
    /* ------------------------------------------------- */

    /**
     * @var ?int L'Identifiant de l'article
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var ?string Le titre de l'article
     */
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    /**
     * @var ?string Le contenu de l'article
     */
    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    /**
     * @var ?\DateTimeImmutable La dâte de l'articlé
     */
    #[ORM\Column]
    private ?\DateTimeImmutable $date = null;

    /**
     * @var ?User L'Utilisateur associé à l'article
     */
    #[ORM\ManyToOne(inversedBy: 'articles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    /* ----------------------------------------------- */
    /* ------------------- GETTERS ------------------- */
    /* ----------------------------------------------- */

    /** 
     * Retourne l'Identifiant associé à l'article.
     * 
     * @return ?int Un entier représentant l'identifiant de l'article en question.
     */
    public function getId(): ?int { return $this->id; }

    /** 
     * Retourne le titre associé à l'article.
     * 
     * @return ?string Une chaîne de caractère stockant le titre de l'article
     */
    public function getTitle(): ?string { return $this->title; }
    
    /** 
     * Retourne le contenu associé à l'article.
     * 
     * @return ?string Une chaîne de caractère stockant le contenu de l'article
     */
    public function getContent(): ?string { return $this->content; }

    /** 
     * Retourne la dâte associé à l'article.
     * 
     * @return ?\DateTimeImmutable La dâte associé à l'article
     */
    public function getDate(): ?\DateTimeImmutable { return $this->date; }

    /** 
     * Retourne l'utilisateur associé à l'article.
     * 
     * @return ?User L'Utilisateur à l'article
     */
    public function getUser(): ?User { return $this->user; }


    /* ----------------------------------------------- */
    /* ------------------- SETTERS ------------------- */
    /* ----------------------------------------------- */
    
    /**
     * Définit le titre de l'article.
     * 
     * @param string Le titre en question à donné à l'article.
     * 
     * @return self L'Article en question.
     */
    public function setTitle(string $title): self {

        $this->title = $title;
        return $this;
    }

    /**
     * Définit le contenu de l'article.
     * 
     * @param string Le contenu en question à donné à l'article.
     * 
     * @return self L'Article en question.
     */
    public function setContent(string $content): self {

        $this->content = $content;
        return $this;
    }

    /**
     * Définit la dâte de l'article.
     * 
     * @param \DateTimeImmutable La dâte en question à donnée à l'article.
     * 
     * @return self L'Article en question.
     */
    public function setDate(\DateTimeImmutable $date): self {

        $this->date = $date;
        return $this;
    }

    /**
     * Définit l'Utilisateur de l'article.
     * 
     * @param ?User L'Utilisateur en question à donnée à l'article.
     * 
     * @return self L'Article en question.
     */
    public function setUser(?User $user): self {

        $this->user = $user;
        return $this;
    }
}