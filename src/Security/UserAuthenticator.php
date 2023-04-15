<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class UserAuthenticator extends AbstractLoginFormAuthenticator {

    // Trait permettant d'obtenir (et de définir) la dernière URL visitée par l'utilisateur avant d'être obligé de s'authentifier.
    use TargetPathTrait; 
    
    // Définit la route de connexion de l'utilisateur
    public const LOGIN_ROUTE = 'login_user'; 

                        /* -------------------------------------------------------------- */

    public function __construct(private UrlGeneratorInterface $urlGenerator) {}

                        /* -------------------------------------------------------------- */                        
                        /* -------------------------------------------------------------- */
                        /* -------------------------------------------------------------- */

    /**
     * Authentifie l'Utilisateur en question dans le site
     * 
     * @param Request $request La requête en question
     * 
     */
    public function authenticate(Request $request): Passport {

        $email = $request->request->get('email', '');
        $request->getSession()->set(Security::LAST_USERNAME, $email);

        return new Passport(new UserBadge($email), new PasswordCredentials($request->request->get('password', '')),
                             [ new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')) ]);
    }

                        /* -------------------------------------------------------------- */                        
                        /* -------------------------------------------------------------- */

    /**
     * Vérifie si l'Utilisateur à bien été authentifié
     * 
     * @param Request $request La requête en question
     * @param TokenInterface $token Une instance de 'TokenInterface'
     * @param string $firewallName Le nom du pare-feu de L'Utilisateur pour la connexion
     * 
     * 
     * @return ?Response Une redirection HTTP si tous est ok, sinon une exception
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response {

        if($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) { return new RedirectResponse($targetPath); }

        // For example:
        // return new RedirectResponse($this->urlGenerator->generate('some_route'));
        throw new \Exception('TODO : fournit une redirection valide à l\'intérieur de '.__FILE__);
    }
    
                        /* -------------------------------------------------------------- */                        
                        /* -------------------------------------------------------------- */
    
    /**
     * Récupère le lien de la page de connexion Utilisateur
     * 
     * @param Request $request La requête en question
     * 
     * @return string Chaîne de caractère retournant le lien du route du formulaire de connexion Utilisateur
     */
    protected function getLoginUrl(Request $request): string { return $this->urlGenerator->generate(self::LOGIN_ROUTE); }
}
