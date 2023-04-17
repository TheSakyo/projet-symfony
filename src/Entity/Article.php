<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[Vich\Uploadable]
class Article implements \Serializable {

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
     * @var ?string Le nom de l'image associé à l'article
     */
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    /**
     * @var File|null Le fichier de l'image associé à l'article
     */
    #[Vich\UploadableField(mapping: "article_img", fileNameProperty: "image")]
    private $imageFile = null;

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

    /**
     * @var Collection Les commentaires associé à l'article
     */
    #[ORM\OneToMany(mappedBy: 'article', targetEntity: Comment::class, orphanRemoval: true)]
    private Collection $comments;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'articles')]
    private Collection $tag;

    /* ---------------------------------------------------- */
    /* ------------------- CONSTRUCTEUR ------------------- */
    /* ---------------------------------------------------- */

    
    /**
     * Constructeur de l'Article.
     * 
     */
    public function __construct() { $this->comments = new ArrayCollection();
    $this->tag = new ArrayCollection();  }


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
     * Retourne le nom de l'image associé à l'article.
     * 
     * @return ?string Une chaîne de caractère stockant le nom de l'image de l'article
     */
    public function getImage(): ?string { return $this->image; }

    /** 
     * Retourne le fichier de l'image associé à l'article.
     * 
     * @return ?File Une chaîne de caractère stockant le fichier de l'image de l'article
     */
    public function getImageFile(): ?File { return $this->imageFile; }

    /** 
     * Retourne la dâte associé à l'article.
     * 
     * @return ?\DateTimeImmutable La dâte associé à l'article
     */
    public function getDate(): ?\DateTimeImmutable { return $this->date; }

    /** 
     * Retourne l'utilisateur associé à l'article.
     * 
     * @return ?User L'Utilisateur associé à l'article
     */
    public function getUser(): ?User { return $this->user; }

        
                    /* ------------------------------------------------------- */

    /**
     * Retourne tous les commentaires de l'article.
     * 
     * @return Collection<int, Comment> Les commentaires associés à l'article
     */
    public function getComments(): Collection { return $this->comments; }

    /**
     * Retourne toutes les catégories de l'article.
     * 
     * @return Collection<int, Tag> Les catégories associées à l'article
     */
    public function getTag(): Collection { return $this->tag; }

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
     * Définit le nom de l'image de l'article.
     * 
     * @param ?string Le nom de l'image en question à donnée à l'article.
     * 
     * @return self L'Article en question.
     */
    public function setImage(?string $image): self {
        
        $this->image = $image;
        return $this;
    }

    /**
     * Définit le fichier de l'image de l'article.
     * 
     * @param ?File Le fichier de l'image en question à donnée à l'article.
     * 
     * @return self L'Article en question.
     */
    public function setImageFile(?File $imageFile = null): self {

        $this->imageFile = $imageFile;
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
    
    /* ------------------------------------------------ */
    /* ------------------- MÉTHODES ------------------- */
    /* ------------------------------------------------ */

    /**
     * Ajoute un commentaire qui sera associé à l'article.
     * 
     * @param Comment Le Commentaire à ajouté en question.
     * 
     * @return self L'Article en question.
     */
    public function addComment(Comment $comment): self {

        if(!$this->comments->contains($comment)) {

            $this->comments->add($comment);
            $comment->setArticle($this);
        }

        return $this;
    }

    /**
     * Supprime un commentaire qui sera associé à l'article.
     * 
     * @param Comment Le commentaire à ajouté en question.
     * 
     * @return self L'Article en question.
     */
    public function removeComment(Comment $comment): self {

        if($this->comments->removeElement($comment)) {

            // donne la valeur null au côté propriétaire (à moins qu'il n'ait déjà été modifié)
            if($comment->getArticle() === $this) { $comment->setArticle(null); }
        }

        return $this;
    }

    /**
     * Ajoute une catégorie qui sera associé à l'article.
     * 
     * @param Tag La catégorie à ajoutée en question.
     * 
     * @return self L'Article en question.
     */
    public function addTag(Tag $tag): self {
        
        if(!$this->tag->contains($tag)) { $this->tag->add($tag); }
        return $this;
    }

    /**
     * Supprime une catégorie qui sera associé à l'article.
     * 
     * @param Tag La catégorie à ajoutée en question.
     * 
     * @return self L'Article en question.
     */
    public function removeTag(Tag $tag): self
    {
        $this->tag->removeElement($tag);

        return $this;
    }

                /* ------------------------------------------- */

                
    public function serialize() { return serialize($this); }
    public function unserialize($serialized) {}

}
