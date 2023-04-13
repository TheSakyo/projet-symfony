<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\Authenticator\LoginFormType;
use App\Form\Authenticator\RegistrationFormType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
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

    #[Route(path: '/login', name: 'login_user')]
    public function login(Request $request, AuthenticationUtils $authenticationUtils): Response {

        $form = $this->form($request, LoginFormType::class);

            /* ----------------------------------------------------------- */

        $parameters['error'] = $authenticationUtils->getLastAuthenticationError();
        $parameters['last_username'] = $authenticationUtils->getLastUsername();

            /* ----------------------------------------------------------- */

        if($form->isSubmitted()) {

            if($form->isValid()) {

                $email = $form->get('email')->getData();
                $password = $form->get('password')->getData();

                                        /* ------------------------ */

                // Vérifiez les informations de connexion de l'utilisateur
                $user = $this->userRepository->findOneBy(['email' => $email]);

                if(!$user || !password_verify($password, $user->getPassword())) {

                    $parameters['errorsForm'] = 'Adresse email ou mot de passe incorrect';
                    return $this->redirectToRoute('login_user');
                }
            
                // ⬇️ Connecte l'utilisateur ⬇️ //
                $token = new UsernamePasswordToken($user, 'main', $user->getRoles()); 
                $this->tokenStorage->setToken($token);
                $this->session->set('_security_main', serialize($token));   
                // ⬆️ Connecte l'utilisateur ⬆️ // 

                                    /* --------------------------- */

                return $this->redirectToRoute('home');   

            } else { 
                
                $parameters['errorsForm'] = LoginFormType::checkErrors($form); 
            }
        } 
                                /* --------------------------------------- */

        $parameters['form'] = $form->createView();
        return $this->index('authenticate/login.html.twig', $parameters);
    }

                    /* ----------------------------------------------------------------------- */

    #[Route('/register', name: 'register_user')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher): Response {
        
        $user = new User();
        $form = $this->form($request, RegistrationFormType::class, $user);

            /* ----------------------------------------------------------- */

        if($form->isSubmitted()) {

            if($form->isValid()) {

                $user->setPassword($userPasswordHasher->hashPassword($user, $user->getPassword())); // Crypte le mot de passe pour l'utilisateur
                $user->setRoles(["ROLE_USER"]); // Ajoute le rôle par défaut de l'utilisateur
                
                                /* ------------------------------------------- */

                $this->userRepository->save($user, true);

                                    /* --------------------------- */

                return $this->redirectToRoute('login_user'); // Redirige l'utilisateur dans la page de login

            } else { $parameters['errorsForm'] = RegistrationFormType::checkErrors($form); }
        } 

            /* ----------------------------------------------------------- */

        $parameters['form'] = $form->createView();
        return $this->index('authenticate/register.html.twig', $parameters);
    }


                    /* ----------------------------------------------------------------------- */

    #[Route(path: '/logout', name: 'logout_user')]
    public function logout(Request $request): Response { return $this->redirectToRoute('login_user'); }
}
