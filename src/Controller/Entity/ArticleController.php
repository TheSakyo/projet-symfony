<?php

namespace App\Controller\Entity;

use DateTimeImmutable;
use App\Controller\MainController;
use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\User;
use App\Form\Entity\ArticleFormType;
use App\Form\Entity\CommentFormType;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/article')]
class ArticleController extends MainController {
    
    private $articleRepository;
    public function __construct(ArticleRepository $articleRepository) { $this->articleRepository = $articleRepository; }
    
                    /* ----------------------------------------------------------------------- */
                    /* ----------------------------------------------------------------------- */

    /**
     * Récupère l'info de l'article pour l'afficher à sa vue
     * 
     * @param Request $request La requête en question
     * @param Article $article L'Article à récupérer
     * 
     * @return Response Une vue associé 
     */                
    #[Route('/info_{id}/{id_comment}', name: 'article_info')]
    public function info(Request $request, Article $article, CommentRepository $commentRepository): Response {    

        $user = $this->getUser(); // Récupère l'Utilisateur connecté
        $commentID = $request->attributes->get('id_comment'); // Récupère l'identifant du commentaire en 'GET'

                                    /* ---------------------------- */

        
        // Si on arrive à récupérer l'identifiant du commentaire en 'GET, on récupère le commentaire en question
        if($commentID != 'null') { 

            $comment = $commentRepository->find($commentID); 
            $isDelete = $request->query->get('delete');
                
            if($isDelete && $isDelete =='true') {

                $commentRepository->remove($comment, true); 
                return $this->redirectToRoute('article_info', ['id' => $article->getId(), 'id_comment' => 'null']);
            }
        } 
        
        // Sinon, on instancie un nouveau commentaire
        else { $comment = new Comment();  }

        $form = $this->form($request, CommentFormType::class, $comment); // Récupère le formulaire du commentaire

                        /* ----------------------------------------------------------- */

        if($form->isSubmitted() && $form->isValid() && $user) {

            $comment->setArticle($article);
            $comment->setUser($user);
            $comment->setDate(new DateTimeImmutable());
            $commentRepository->save($comment, true); 
            return $this->redirectToRoute('article_info', ['id' => $article->getId(), 'id_comment' => 'null']);
        }

                        /* ----------------------------------------------------------- */

        // Retourne la vue avec les paramètres associés //
        return $this->index('entities/article/info.html.twig', [
            
            'form' => $form->createView(),
            'article' => $article
        ]);
        // Retourne la vue avec les paramètres associés //
    }

            /* ----------------------------------------------------------- */
            /* ----------------------------------------------------------- */
       
    /**
     * Récupère tous les articles pour l'afficher à sa vue
     * 
     * @param Request $request La requête en question
     * @param PaginatorInterface $paginator Un système de pagination
     * 
     * @return Response Une vue associé 
     */ 
    #[Route('/', name: 'article_list')]
    public function showAll(Request $request, PaginatorInterface $paginator): Response {
    
        // Stocke tous les articles ordonné par date
        $allArticle = $this->articleRepository->findBy([], ['date' => 'DESC']); 

        // Récupère tous les articles avec une pagination
        $articlesWithPagination = $paginator->paginate($allArticle, $request->query->getInt('page', 1), 25); 

                        /* ----------------------------------------------------------- */

        // Retourne la vue avec les paramètres associés //
        return $this->index('entities/article/index.html.twig',[
            
            'route' => 'article_list',
            'total' => $articlesWithPagination->getTotalItemCount(),
            'articles' => $articlesWithPagination
        ]);
        // Retourne la vue avec les paramètres associés //
    }

            /* ----------------------------------------------------------- */
            /* ----------------------------------------------------------- */
       
    /**
     * Récupère tous les articles d'un seul Utilisateur pour l'afficher à sa vue
     * 
     * @param Request $request La requête en question
     * @param PaginatorInterface $paginator Un système de pagination
     * 
     * @return Response Une vue associé 
     */ 
    #[Route('/yourArticles', name: 'articles_user')]
    public function show(Request $request, PaginatorInterface $paginator): Response {

        /** @var ?UserInterface */      
        $user = $this->getUser(); // Récupère l'Utilisateur connecté

                        /* --------------------------------------- */

        // Permettra de récupérer les articles de l'Utilisateur
        $userArticles = null; 

        // Permettra de récupérer les articles de l'Utilisateur en intégrant un système de pagination
        $articlesWithPagination = null; 

        // Permettra de récupérer le nombre total de page
        $total = null; 

        // Permettra de récupérer une erreur si l'utilisateur n'est pas connecté
        $error = null; 
                
                        /* --------------------------------------- */

         // Si l'utilisateur est connecté, on récupère ses articles associées
         if($user) { 

            $userArticles = $user->getArticles(); // Stocke la liste des articles de l'utilisateur

            // Récupère tous les articles de l'utilisateur avec une pagination
            $articlesWithPagination = $paginator->paginate($userArticles, $request->query->getInt('page', 1), 25);
            
            // Récupère le nombe total de page
            $total = $articlesWithPagination->getTotalItemCount();
        
        // Sinon, on récupère un message d'erreur
        } else { $error = "Vous n'êtes pas connecté, veuillez vous connecter pour interagir avec vos articles";  }    

                        /* ----------------------------------------------------------- */

        // Retourne la vue avec les paramètres associés //
        return $this->index('entities/article/userArticles.html.twig',[
            
            'route' => 'articles_user',
            'total' => $total,
            'articles' => $articlesWithPagination,
            'error' => $error
        ]);
        // Retourne la vue avec les paramètres associés //
    }
    
            /* ----------------------------------------------------------- */
            /* ----------------------------------------------------------- */

    /**
     * Essaie d'ajouter un Article si le formulaira en question est envoyé ensuite on l'affiche à sa vue d'information
     * 
     * @param Request $request La requête en question
     * 
     * @return Response Une vue associé ou une redirection de route
     */             
    #[Route('/add', name: 'formAdd_article' )]
    public function add(Request $request): Response {
        
        /** @var ?User */    
        $user = $this->getUser(); // Récupère l'Utilisateur connecté
        $article = new Article(); // Instancie un nouvel article
        $form = $this->form($request, ArticleFormType::class, $article); // Récupère le formulaire de l'article

                        /* --------------------------------------- */
        
        // Si l'utilisateur n'est pas connecté, on retourne à la liste des articles (une erreur sera envoyé)
        if(!$user) { return $this->redirectToRoute('articles_user'); } 

                        /* --------------------------------------- */

        // Si le formulaire à bien été envoyé et qu'il est valide, on créer l'article
        if($form->isSubmitted() && $form->isValid()) {
            
            /** @var ?UploadedFile */  
            $image = $article->getImageFile(); // Récupère le fichier d'image uploadé

            // Si l'image existe bien, on l'ajoute à l'article en récupérant son nom original
            if($image != null) { $article->setImage($image->getClientOriginalName()); }  

                            /* ------------------------------------------- */

            $article->setUser($user); // Attribue à l'article ajouté l'Utilisateur en question
            $article->setDate(new DateTimeImmutable()); // Ajoute la date d'envoi de l'article

                            /* ------------------------------------------- */
                            
            $this->articleRepository->save($article, true); // Sauvegarde l'article vers la base de données
            $user->addArticle($article); // Attribue à l'Utilisateur en question l'article ajouté

                                /* --------------------------- */

            return $this->redirect('/article/info_'.$article->getId()."/null"); // Redirige l'utilisateur dans la liste de ses articles
        } 

                            /* -------------------------------------- */

        $errorsForm = ArticleFormType::checkErrors($form); // Permet de checker les erreurs de formulaire

                        /* ----------------------------------------------------------- */

        // Retourne la vue avec les paramètres associés //
        return $this->index('entities/article/add.html.twig', [

            'form' => $form->createView(),
            'errorsForm' => $errorsForm,
            'article' => $article,
        ]);
        // Retourne la vue avec les paramètres associés //
    }

            /* ----------------------------------------------------------- */
            /* ----------------------------------------------------------- */

    /**
     * Essaie de mettre à jour un Article si le formulaira en question est envoyé ensuite on l'affiche à sa vue d'information
     * 
     * @param Request $request La requête en question
     * @param Article $article L'Article à mettre à jour
     * 
     * @return Response Une vue associé ou une redirection de route
     */     
    #[Route('/update_{id}', name: 'formUpdate_article')]
    public function update(Request $request, Article $article): Response {
        
        $user = $this->getUser(); // Récupère l'Utilisateur connecté
        $form = $this->form($request, ArticleFormType::class, $article); // Récupère le formulaire

                        /* --------------------------------------- */
        
        // Si l'utilisateur n'est pas connecté, on retourne à la liste des articles (une erreur sera envoyé)
        if(!$user) { return $this->redirectToRoute('articles_user'); } 

                        /* --------------------------------------- */

        // Si le formulaire à bien été envoyé et qu'il est valide, on met à jour l'article
        if($form->isSubmitted() && $form->isValid()) {

            /** @var ?UploadedFile */  
            $image = $article->getImageFile(); // Récupère le fichier d'image uploadé

            // Si l'image existe bien, on l'ajoute à l'article en récupérant son nom original
            if($image != null) { $article->setImage($image->getClientOriginalName()); }  

                            /* ------------------------------------------- */
        
            $this->articleRepository->save($article, true); // Sauvegarde l'article vers la base de données

                                /* --------------------------- */

            return $this->redirect('/article/info_'.$article->getId()."/null"); // Redirige l'utilisateur dans la liste de ses articles
        } 

                            /* -------------------------------------- */

        $errorsForm = ArticleFormType::checkErrors($form); // Permet de checker les erreurs de formulaire

                        /* ----------------------------------------------------------- */
        
        // Retourne la vue avec les paramètres associés //
        return $this->index('entities/article/update.html.twig', [

            'form' => $form->createView(),
            'errorsForm' => $errorsForm,
            'article' => $article,
        ]);
        // Retourne la vue avec les paramètres associés //
    }

            /* ----------------------------------------------------------- */
            /* ----------------------------------------------------------- */

    /**
     * Essaie de supprimer un Article
     * 
     * @param Request $request La requête en question
     * @param Article $article L'Article à supprimer
     * 
     * @return Response Une redirection de route
     */  
    #[Route('/delete_{id}', name: 'formDelete_article')]
    public function delete(Request $request, Article $article): Response {

        // Si le jeton CRSF est valide, on peut supprimer l'article
        if($this->isCsrfTokenValid('delete'.$article->getId(), $request->query->get('token'))) { $this->articleRepository->remove($article, true); }
    
                        /* --------------------------------------- */           

        return $this->redirectToRoute('articles_user'); // On retourne à la dernière route visité récupéré en session
    }
}
