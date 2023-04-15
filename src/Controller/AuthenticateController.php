<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Authenticator\LoginFormType;
use App\Form\Authenticator\RegistrationFormType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class AuthenticateController extends MainController {

    private $userRepository;
    private $tokenStorage;
    private $session;

    public function __construct(UserRepository $userRepository, TokenStorageInterface $tokenStorage, SessionInterface $session) { 

        $this->userRepository = $userRepository; 
        $this->tokenStorage = $tokenStorage;
        $this->session = $session;
    }
    
    
                    /* ----------------------------------------------------------------------- */
                    /* ----------------------------------------------------------------------- */

    /**
     * Récupère une page de connexion utilisateur pour l'afficher à sa vue
     * 
     * @param Request $request La requête en question
     * 
     * @return Response Une vue associé ou une redirection de route
     */     
    #[Route(path: '/login', name: 'login_user')]
    public function login(Request $request): Response {

        $user = $this->getUser(); // Récupère l'Utilisateur connecté
        $form = $this->form($request, LoginFormType::class); // Récupère le formulaire de connexion

                        /* --------------------------------------- */

        // Si l'utilisateur est pas connecté, on retourne à la page d'accueil
        if($user) { return $this->redirectToRoute('home'); } 

                        /* --------------------------------------- */

        // Si le formulaire à bien été envoyé et qu'il est valide, on essaie de connecter l'utilisateur
        if($form->isSubmitted() && $form->isValid()) {

            $email = $form->get('email')->getData(); // Récupère l'adresse mail depuis le formulaire
            $password = $form->get('password')->getData(); // Récupère le mot de passe depuis le formulaire

                                /* --------------------------- */

            // Récupère l'utilisateur par l'adresse mail récupéré dans le formulaire
            $userToConnect = $this->userRepository->findOneBy(['email' => $email]);


            /* ⬇️ Si l'utilisateur n'existe pas et que son mot de passe crypté n'est pas correspondant
              à celui récupéré dans le formulaire, alors on définit une erreur ⬇️ */
            if(!$userToConnect || !password_verify($password, $userToConnect->getPassword())) {

                // Définit une erreur disant que c'est les champs sont incorrect
                $errorsForm[] = 'Adresse mail ou mot de passe incorrect'; 

                // Retourne la vue avec les paramètres associés //
                return $this->index('authenticate/login.html.twig', [
                    
                    'form' => $form->createView(),
                    'errorsForm' => $errorsForm
                ]);
                // Retourne la vue avec les paramètres associés //
            }
            /* ⬆️ Si l'utilisateur n'existe pas et que son mot de passe crypté n'est pas correspondant
              à celui récupéré dans le formulaire, alors on définit une erreur ⬆️ */
            
                        /* ------------------------------------------------ */

            // ⬇️ Connecte l'Utilisateur ⬇️ //
            $token = new UsernamePasswordToken($userToConnect, 'main', $userToConnect->getRoles()); 
            $this->tokenStorage->setToken($token);
            $this->session->set('_security_main', serialize($token));   
            // ⬆️ Connecte l'Utilisateur ⬆️ // 

                                /* --------------------------- */
 
            return $this->redirectToRoute('home'); // On retourne à la page d'accueil 
        } 
                        /* --------------------------------------- */

        $errorsForm = LoginFormType::checkErrors($form); // Permet de checker les erreurs de formulaire

            /* ----------------------------------------------------------- */

        // Retourne la vue avec les paramètres associés //
        return $this->index('authenticate/login.html.twig', [
            
            'form' => $form->createView(),
            'errorsForm' => $errorsForm
        ]);
        // Retourne la vue avec les paramètres associés //
    }

            /* ----------------------------------------------------------- */
            /* ----------------------------------------------------------- */

    /**
     * Essaie de créer un nouvel utilisateur ensuite on affiche l'utilisateur à la page d'accueil
     * 
     * @param Request $request La requête en question
     * @param UserPasswordHasherInterface $userPasswordHasher Ceci permettra de définir un mot de passe crypté
     * 
     * @return Response Une vue associé ou une redirection de route
     */    
    #[Route('/register', name: 'register_user')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response {
        
        $user = new User(); // Instancie un nouvelle utilisateur
        $form = $this->form($request, RegistrationFormType::class, $user); // Récupère le formulaire d'inscription

            /* ----------------------------------------------------------- */

        // Si le formulaire à bien été envoyé et qu'il est valide, on créer l'utilisateur
        if($form->isSubmitted() && $form->isValid()) {

            $user->setPassword($userPasswordHasher->hashPassword($user, $user->getPassword())); // Crypte le mot de passe pour l'utilisateur
            $user->setRoles(["ROLE_USER"]); // Ajoute le rôle par défaut de l'utilisateur
            
                            /* ------------------------------------------- */

            $this->userRepository->save($user, true); // Sauvegarde l'utilisateur vers la base de données

                            /* ------------------------------------------- */

            return $this->redirectToRoute('login_user'); // Redirige l'utilisateur dans la page de login
        } 

                        /* --------------------------------------- */

        $errorsForm = RegistrationFormType::checkErrors($form); // Permet de checker les erreurs de formulaire

            /* ----------------------------------------------------------- */

        return $this->index('authenticate/register.html.twig', [

            'form' => $form->createView(),
            'errorsForm' => $errorsForm
        ]);
    }

            /* ----------------------------------------------------------- */
            /* ----------------------------------------------------------- */
           
    /**
     * Essaie de déconnecter un utilisateur ensuite on affiche l'utilisateur à la page de connexion
     * 
     * @param Request $request La requête en question
     * 
     * @return Response Une redirection de route
     */ 
    #[Route(path: '/logout', name: 'logout_user')]
    public function logout(Request $request): Response { return $this->redirectToRoute('login_user'); }
}
