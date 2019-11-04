<?php

namespace App\Security;


use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class Passage2Authenticator extends AbstractGuardAuthenticator
{

    use TargetPathTrait;


    private $urlGenerator;


    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;

    }

    public function supports(Request $request)
    {


        return $request->headers->has('X-AUTH-RIO') && 'app_logout' !== $request->attributes->get('_route')
            && 'hello' !== $request->attributes->get(
                '_route'
            )
            && 'app_login' !== $request->attributes->get('_route');
    }

    public function getCredentials(Request $request)
    {
        return [
            'rio' => $request->headers->get('X-AUTH-RIO'),
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $rio = $credentials['rio'];

        if ( null === $rio ) {
            return;
        }

        //dd($userProvider->loadUserByUsername($rio));
        return $userProvider->loadUserByUsername($rio);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {


        /**
         * @var Session $session
         */
        $session = $request->getSession();
        $session->getFlashBag()->add('error', strtr($exception->getMessageKey(), $exception->getMessageData()));

        return new RedirectResponse($this->urlGenerator->generate('app_login'));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {

    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        // todo
    }

    /**
     * Does this method support remember me cookies?
     *
     * Remember me cookie will be set if *all* of the following are met:
     *  A) This method returns true
     *  B) The remember_me key under your firewall is configured
     *  C) The "remember me" functionality is activated. This is usually
     *      done by having a _remember_me checkbox in your form, but
     *      can be configured by the "always_remember_me" and "remember_me_parameter"
     *      parameters under the "remember_me" firewall key
     *  D) The onAuthenticationSuccess method returns a Response object
     *
     * @return bool
     */
    public function supportsRememberMe()
    {
        return false;
    }
}
