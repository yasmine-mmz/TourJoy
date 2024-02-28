<?php

namespace App\Security;

use App\Entity\User; // your user entity
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;


class GoogleCustomAuthenticator extends OAuth2Authenticator implements AuthenticationEntrypointInterface
{
    private $clientRegistry;
    private $entityManager;
    private $router;
    private $passwordHasher;
    private $emailVerifier;

    public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $entityManager, RouterInterface $router, UserPasswordHasherInterface $passwordHasher, EmailVerifier $emailVerifier)
    {
        $this->clientRegistry = $clientRegistry;
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->passwordHasher = $passwordHasher;
        $this->emailVerifier = $emailVerifier;
    }

    private function generateRandomPassword($length = 12) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_-=+;:,<.>?';
        $charactersLength = strlen($characters);
        $randomPassword = '';
        for ($i = 0; $i < $length; $i++) {
            $randomPassword .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomPassword;
    }

    public function supports(Request $request): ?bool
    {
                return $request->attributes->get('_route') === 'connect_google_check';
    }

    public function authenticate(Request $request): Passport
    {
        $client = $this->clientRegistry->getClient('google_main');
        $accessToken = $this->fetchAccessToken($client);

        return new SelfValidatingPassport(
            new UserBadge($accessToken->getToken(), function () use ($accessToken, $client) {
                /** @var \League\OAuth2\Client\Provider\GoogleUser $googleUser */
                $googleUser = $client->fetchUserFromToken($accessToken);
                $email = $googleUser->getEmail();

                $user = $this->entityManager->getRepository(User::class)->findOneBy(['googleId' => $googleUser->getId()])
                    ?? $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

                $placeholderPassword = null;

                if (!$user) {
                    $user = new User();
                    $user->setEmail($email);
                    $user->setCreatedAt(new \DateTimeImmutable());
                    $user->setFirstName('firstname');
                    $user->setLastName('lastname');

                    $placeholderPassword = $this->generateRandomPassword(16);

                    $hashedPassword = $this->passwordHasher->hashPassword($user, $placeholderPassword);
                    $user->setPassword($hashedPassword);
                    $user->setGoogleId($googleUser->getId());

                    // Persist the user entity
                    $this->entityManager->persist($user);
                    $this->entityManager->flush();

                    $this->emailVerifier->sendEmailConfirmation(
                        'app_verify_email',
                        $user,
                        (new TemplatedEmail())
                            ->from(new Address('no-reply@tourjoy.com', 'NoReply'))
                            ->to($user->getEmail())
                            ->subject('Please Confirm your Email')
                            ->htmlTemplate('registration/confirmation_email_google.html.twig')
                            ->context([
                                'temporaryPassword' => $placeholderPassword,
                            ])
                    );
                }
                return $user;
            })
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $targetUrl = $this->router->generate('app_test');

        return new RedirectResponse($targetUrl);

    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    /**
     * Called when authentication is needed, but it's not sent.
     * This redirects to the 'login'.
     */
    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        return new RedirectResponse(
            '/connect/',
            Response::HTTP_TEMPORARY_REDIRECT
        );
    }

    
}
