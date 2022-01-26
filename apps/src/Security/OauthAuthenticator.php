<?php

namespace Labstag\Security;

use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Labstag\Entity\User;
use Labstag\Service\ErrorService;
use Labstag\Service\OauthService;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class OauthAuthenticator extends AbstractAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    protected string $oauthCode;

    protected Request $request;

    // @var string
    protected $route;

    public function __construct(
        protected ErrorService $errorService,
        protected EntityManagerInterface $entityManager,
        protected UrlGeneratorInterface $urlGenerator,
        protected CsrfTokenManagerInterface $csrfTokenManager,
        protected UserPasswordHasherInterface $passwordEncoder,
        protected OauthService $oauthService,
        protected RequestStack $requestStack,
        protected TokenStorageInterface $token,
        protected LoggerInterface $logger
    )
    {
        // @var Request $request
        $request = $this->requestStack->getCurrentRequest();

        $this->request = $request;

        $attributes      = $this->request->attributes;
        $oauthCode       = $this->setOauthCode($attributes);
        $this->oauthCode = $oauthCode;
    }

    public function authenticate(Request $request): PassportInterface
    {
        // @var AbstractProvider $provider
        $provider    = $this->oauthService->setProvider($this->oauthCode);
        $attributes  = $request->attributes->all();
        $query       = $request->query->all();
        $session     = $request->getSession();
        $oauth2state = $session->get('oauth2state');
        if (!$provider instanceof AbstractProvider) {
            throw new CustomUserMessageAuthenticationException('No API token provided');
        }

        if (!isset($query['code']) || $oauth2state !== $query['state']) {
            throw new CustomUserMessageAuthenticationException('No API token provided');
        }

        try {
            // @var AccessToken $tokenProvider
            $tokenProvider = $provider->getAccessToken(
                'authorization_code',
                [
                    'code' => $query['code'],
                ]
            );
            // @var mixed $userOauth
            $userOauth = $provider->getResourceOwner($tokenProvider);
            $data      = $userOauth->toArray();
            $client    = $attributes['_route_params']['oauthCode'];
            $identity  = $this->oauthService->getIdentity($data, $client);
            $user      = $this->getRepository(User::class)->findOauth(
                $identity,
                $client
            );

            return new SelfValidatingPassport(
                new UserBadge($user->getUsername())
            );
        } catch (Exception $exception) {
            $this->errorService->set($exception);
            dump($exception);

            throw new CustomUserMessageAuthenticationException('No API token provided');
        }
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        if ($request->hasSession()) {
            $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
        }

        $url = $this->getLoginUrl($request);

        return new RedirectResponse($url);
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

    protected function getLoginUrl(Request $request): string
    {
        unset($request);

        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }

    protected function getRepository(string $entity)
    {
        return $this->entityManager->getRepository($entity);
    }

    protected function setOauthCode(ParameterBag $attributes): string
    {
        if ($attributes->has('oauthCode')) {
            return $attributes->get('oauthCode');
        }

        return '';
    }
}
