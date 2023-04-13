<?php

namespace App\Controller\Entity;

use App\Controller\MainController;
use App\Entity\Article;
use App\Entity\User;
use App\Form\Entity\ArticleFormType;
use App\Repository\ArticleRepository;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfToken;

#[Route('/article')]
class ArticleController extends MainController {

    private $articleRepository;

    public function __construct(ArticleRepository $articleRepository) { $this->articleRepository = $articleRepository; }
    
                    /* ----------------------------------------------------------------------- */
    
    #[Route('/info_{id}', name: 'article_info')]
    public function info(Request $request, Article $article): Response {    

        if($this->isCsrfTokenValid('info'.$article->getId(), $request->query->get('token'))) { $parameters['article'] = $article; }
        return $this->index('entities/article/info.html.twig', $parameters);
    }
    
                    /* ----------------------------------------------------------------------- */

    #[Route('/', name: 'articles_list')]
    public function showAll(): Response {

        $parameters['articles'] = $this->articleRepository->findBy([], ['date' => 'DESC']);
        return $this->index('entities/article/all.html.twig', $parameters);
    }

            /* ---------------------------------------- */
            
    #[Route('/yourArtices', name: 'articles_user')]
    public function show(): Response {

        /** @var ?UserInterface */      
        $user = $this->getUser(); // Récupère l'Utilisateur connecté

        // Si l'utilisateur est connecté, on récupère ses articles associé<es></es>
        if($user) { $parameters['articles'] = $user->getArticles(); } 

        // Sinon, on récupère un message d'erreur
        else { $parameters['error'] = "Vous n'êtes pas connecté, veuillez vous connecter pour interagir avec vos articles";  }

                        /* --------------------------------------- */

        return $this->index('entities/article/index.html.twig', $parameters);
    }
    
            /* ---------------------------------------- */
            
    #[Route('/add', name: 'formAdd_article' )]
    public function add(Request $request): Response {
        
        /** @var ?User */    
        $user = $this->getUser(); // Récupère l'Utilisateur connecté
        $article = new Article(); // Instancie un nouvel article

            /* ----------------------------------------------------------- */

        $form = $this->form($request, ArticleFormType::class, $article);

            /* ----------------------------------------------------------- */
        
        // Si l'utilisateur n'est pas connecté, on retourne à la liste des articles (une erreur sera envoyé)
        if(!$user) { return $this->redirectToRoute('articles_user'); } 

            /* ----------------------------------------------------------- */

        if($form->isSubmitted()) {

            if($form->isValid()) {

                /** @var ?UploadedFile */    
                $image = $article->getImageFile();

                if($image != null) { $article->setImage($image->getClientOriginalName()); }

                $article->setUser($user);
                $article->setDate(new DateTimeImmutable());

                             /* ------------------------------------------- */

                $this->articleRepository->save($article, true);
                $user->addArticle($article);

                                    /* --------------------------- */

                $token = $this->generateInfoTokenCRSF($article)->getValue(); // Récupère le jeton générer pour l'affichage
                return $this->redirect('/article/info_'.$article->getId().'?token='.$token); // Redirige l'utilisateur dans la liste de ses articles

            } else { $parameters['errorsForm'] = ArticleFormType::checkErrors($form); }
        } 

            /* ----------------------------------------------------------- */

        $parameters['form'] = $form->createView();
        return $this->index('entities/article/add.html.twig', $parameters);
    }

            /* ---------------------------------------- */


    #[Route('/update_{id}', name: 'formUpdate_article')]
    public function update(Request $request, Article $article): Response {
 
        $user = $this->getUser(); // Récupère l'Utilisateur connecté
        $parameters['article'] = $article; // Récupère l'article pour la vue

            /* ----------------------------------------------------------- */

        $form = $this->form($request, ArticleFormType::class, $article);

            /* ----------------------------------------------------------- */
        
        // Si l'utilisateur n'est pas connecté, on retourne à la liste des articles (une erreur sera envoyé)
        if(!$user) { return $this->redirectToRoute('articles_user'); } 

            /* ----------------------------------------------------------- */

        if($form->isSubmitted()) {

            if($form->isValid()) {

                 /** @var ?UploadedFile */  
                $image = $article->getImageFile();

                if($image != null) { $article->setImage($image->getClientOriginalName()); }  

                                /* ------------------------------------------- */
              
                $this->articleRepository->save($article, true);

                                    /* --------------------------- */

                $token = $this->generateInfoTokenCRSF($article)->getValue(); // Récupère le jeton générer pour l'affichage
                return $this->redirect('/article/info_'.$article->getId().'?token='.$token); // Redirige l'utilisateur dans la liste de ses articles

            } else { $parameters['errorsForm'] = ArticleFormType::checkErrors($form); }
        } 

            /* ----------------------------------------------------------- */

        $parameters['form'] = $form->createView();    
        return $this->index('entities/article/update.html.twig', $parameters);
    }

                /* ---------------------------------------- */

    #[Route('/delete_{id}', name: 'formDelete_article')]
    public function delete(Request $request, Article $article): Response {

        if($this->isCsrfTokenValid('delete'.$article->getId(), $request->query->get('token'))) { $this->articleRepository->remove($article, true); }
        return $this->redirectToRoute('articles_user');
    }

            /* ---------------------------------------- */
            /* ---------------------------------------- */

    /**
     * Retourne un jeton CRSF généré (permet une certaine sécurisation) pour l'affichage d'un article
     * 
     * @param Article L'Article en question à générer le jeton
     * 
     * @return CsrfToken Retourne une instance de 'CrsfToken'
     */
    private function generateInfoTokenCRSF(Article $article): CsrfToken {

                 /* --------------------------- */

        $csrf = $this->container->get('security.csrf.token_manager');
        return $csrf->refreshToken('info'.$article->getId());
                            
                 /* --------------------------- */
    }
}
