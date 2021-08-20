<?php

namespace Labstag\Security;

use Labstag\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Labstag\Entity\OauthConnectUser;
use Labstag\Repository\OauthConnectUserRepository;
use Labstag\Service\OauthService;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use Symfony\Component\Security\Core\Security;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\{
    AbstractFormLoginAuthenticator
};
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class OauthAuthenticator extends AbstractAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    protected CsrfTokenManagerInterface $csrfTokenManager;

    protected EntityManagerInterface $entityManager;

    protected LoggerInterface $logger;

    protected string $oauthCode;

    protected OauthService $oauthService;

    protected UserPasswordHasherInterface $passwordEncoder;

    protected Request $request;

    protected RequestStack $requestStack;

    /**
     * @var string
     */
    protected $route;

    protected TokenStorageInterface $token;

    protected UrlGeneratorInterface $urlGenerator;

    public function __construct(
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator,
        CsrfTokenManagerInterface $csrfTokenManager,
        UserPasswordHasherInterface $passwordEncoder,
        OauthService $oauthService,
        RequestStack $requestStack,
        TokenStorageInterface $token,
        LoggerInterface $logger
    )
    {
        $this->logger           = $logger;
        $this->entityManager    = $entityManager;
        $this->urlGenerator     = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder  = $passwordEncoder;
        $this->requestStack     = $requestStack;
        /** @var Request $request */
        $request = $this->requestStack->getCurrentRequest();

        $this->request      = $request;
        $this->oauthService = $oauthService;
        $this->token        = $token;

        $attributes      = $this->request->attributes;
        $oauthCode       = $this->setOauthCode($attributes);
        $this->oauthCode = $oauthCode;
    }

    public function supports(Request $request): ?bool
    {
        $session     = $request->getSession()->all();
        $route       = $request->attributes->get('_route');
        $this->route = $route;
        $token       = $this->token->getToken();
        $test1       = 'connect_check' === $route && !array_key_exists('link', $session);
        $test2       = (is_null($token) || !$token->getUser() instanceof User);

        return $test1 && $test2;
    }

    public function authenticate(Request $request): PassportInterface
    {
        // TODO: Implement authenticate() method.
    }

    public function onAuthenticationSuccess(
        Request $request,
        TokenInterface $token,
        string $firewallName
    ): ?Response
    {
        unset($token);
        $targetPath = $this->getTargetPath($request->getSession(), $firewallName);
        if ($targetPath) {
            return new RedirectResponse($targetPath);
        }

        // For example:
        //return new RedirectResponse($this->urlGenerator->generate('some_route'));
        throw new Exception('TODO: provide a valid redirect inside '.__FILE__);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        if ($request->hasSession()) {
            $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
        }

        $url = $this->getLoginUrl($request);

        return new RedirectResponse($url);
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }

    protected function setOauthCode(ParameterBag $attributes): string
    {
        if ($attributes->has('oauthCode')) {
            return $attributes->get('oauthCode');
        }

        return '';
    }
}
