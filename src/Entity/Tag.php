<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\ManyToMany(targetEntity: Article::class, mappedBy: 'tag')]
    private Collection $articles;

    public function __construct()
    {
        $this->articles = new ArrayCollection();
    }

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

            
                    /* ------------------------------------------------------- */

    /**
     * Retourne tous les articles de la catégorie.
     * 
     * @return Collection<int, Article> Les articles associées à la catégorie
     */
    public function getArticles(): Collection { return $this->articles; }

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

    /* ------------------------------------------------ */
    /* ------------------- MÉTHODES ------------------- */
    /* ------------------------------------------------ */

    /**
     * Ajoute un article qui sera associé à la catégorie.
     * 
     * @param Article L'Article à ajouté en question.
     * 
     * @return self La catégorie en question.
     */
    public function addArticle(Article $article): self {

        if(!$this->articles->contains($article)) {

            $this->articles->add($article);
            $article->addTag($this);
        }

        return $this;
    }

    /**
     * Supprime un article qui sera associé à la catégorie.
     * 
     * @param Article L'Article à ajouté en question.
     * 
     * @return self La catégorie en question.
     */
    public function removeArticle(Article $article): self {

        if($this->articles->removeElement($article)) { $article->removeTag($this); }
        return $this;
    }
        
                    /* ------------------------------------------------------- */

    public function __toString() { 
        
        return "Catégorie N°".strval($this->getId()); 
    }
}
