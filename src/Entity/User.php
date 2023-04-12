<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'Il existe déjà un compte avec cet email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface {

    /* ------------------------------------------------- */
    /* ------------------- VARIABLES ------------------- */
    /* ------------------------------------------------- */

    /**
     * @var ?int L'Identifiant de l'utilisateur
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var ?string L'Adresse mail de l'utilisateur
     */
    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    /**
     * @var array Les rôles de l'utilisateur
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var ?string Le mot de passe haché de l'utilisateur
     */
    #[ORM\Column]
    private ?string $password = null;

    
    /**
     * @var ?string Le nom de l'utilisateur
     */
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection Les articles associés à l'utilisateur
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Article::class, orphanRemoval: true)]
    private Collection $articles;


    /* ---------------------------------------------------- */
    /* ------------------- CONSTRUCTEUR ------------------- */
    /* ---------------------------------------------------- */

    
    /**
     * Constructeur de l'Utilisateur.
     * 
     */
    public function __construct() { $this->articles = new ArrayCollection();  }


    /* ----------------------------------------------- */
    /* ------------------- GETTERS ------------------- */
    /* ----------------------------------------------- */
    
    /** 
     * Retourne l'Identifiant associé à l'utilisateur.
     * 
     * @return ?int Un entier représentant l'identifiant de l'utilisateur en question.
     */
    public function getId(): ?int { return $this->id; }

    /**
     * Retourne un identifiant visuel qui représente cet utilisateur.
     *
     * @see UserInterface
     * 
     * @return string Une chaîne de caractère stockant l'identifiant visuel repésentant l'utilisateur
     */
    public function getUserIdentifier(): string { return (string)$this->email; }

    /** 
     * Retourne l'adresse mail associé à l'utilisateur.
     * 
     * @return ?string Une chaîne de caractère stockant l'adresse mail de l'utilisateur
     */
    public function getEmail(): ?string { return $this->email; }

    /** 
     * Retourne le nom associé à l'utilisateur.
     * 
     * @return ?string Une chaîne de caractère stockant le nom de l'utilisateur.
     */
    public function getName(): ?string { return $this->name; }
    
    /**
     * Retourne le mot de passer crypté de l'utilisateur.
     * 
     * @see PasswordAuthenticatedUserInterface
     * 
     * @return string Une tableau chaîne de caractère stockant le mot de passe crypté de l'utilisateur.
     */
    public function getPassword(): string { return $this->password; }

    /**
     * Retourne les rôles associés à l'utilisateur.
     * 
     * @see UserInterface
     * 
     * @return array Un tableau stockant les articles associés à l'utilisateur.
     */
    public function getRoles(): array {

        $roles = $this->roles;
        $roles[] = 'ROLE_USER'; // Garantit que chaque utilisateur a au moins ROLE_USER

        return array_unique($roles);
    }
    
        
    /**
     * Retourne tous les articles de l'utilisateur.
     * 
     * @return Collection<int, Article> 
     */
    public function getArticles(): Collection { return $this->articles; }


    /* ----------------------------------------------- */
    /* ------------------- SETTERS ------------------- */
    /* ----------------------------------------------- */

    /**
     * Définit l'adresse mail de l'utilisateur.
     * 
     * @param string L'Adresse Mail en question à donnée à l'utilisateur.
     * 
     * @return self L'Utilisateur en question.
     */
    public function setEmail(string $email): self {

        $this->email = $email;
        return $this;
    }

    /**
     * Définit le nom de l'utilisateur.
     * 
     * @param string Le nom en question à donné à l'utilisateur.
     * 
     * @return self L'Utilisateur en question.
     */
    public function setName(string $name): self {

        $this->name = $name;
        return $this;
    }

    /**
     * Définit le Mot de passer crypté de l'utilisateur.
     * 
     * @param string Le Mot de passer crypté en question à donné à l'utilisateur.
     * 
     * @return self L'Utilisateur en question.
     */
    public function setPassword(string $password): self {
        
        $this->password = $password;
        return $this;
    }

    /**
     * Définit une liste de rôle(s) de l'utilisateur.
     * 
     * @param string La liste de rôle(s) en question à donnée à l'utilisateur.
     * 
     * @return self L'Utilisateur en question.
     */
    public function setRoles(array $roles): self {

        $this->roles = $roles;
        return $this;
    }

    /* ------------------------------------------------ */
    /* ------------------- MÉTHODES ------------------- */
    /* ------------------------------------------------ */

    /**
     * Ajoute un article qui sera associé à l'utilisateur.
     * 
     * @param Article l'Article à ajouté en question.
     * 
     * @return self L'Utilisateur en question.
     */
    public function addArticle(Article $article): self {

        if(!$this->articles->contains($article)) {

            $this->articles->add($article);
            $article->setUser($this);
        }

        return $this;
    }

    /**
     * Supprime un article qui sera associé à l'utilisateur.
     * 
     * @param Article l'Article à supprimé en question.
     * 
     * @return self L'Utilisateur en question.
     */
    public function removeArticle(Article $article): self {


        if($this->articles->removeElement($article)) {

            // Donne la valeur null au côté propriétaire (à moins qu'il n'ait déjà été modifié)
            if($article->getUser() === $this) { $article->setUser(null); }
        }

        return $this;
    }

    /* ----------------------------------------------- */
    /* ----------------------------------------------- */
    /* ----------------------------------------------- */

    /**
     * Cette méthode permet d'effacer certaines données temporaires donnés à l'utilisateur
     * 
     * @see UserInterface
     */
    public function eraseCredentials() {
        
        // Si vous stockez des données temporaires et sensibles sur l'utilisateur, effacez-les ici
        // $this->plainPassword = null ;
    }

}
