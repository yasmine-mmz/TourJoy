<?php

namespace App\Controller;

use App\Form\UpdateFormType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;




class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_test');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/2fa', name: '2fa_login')]
    public function check2fa(GoogleAuthenticatorInterface $authenticator, TokenStorageInterface $storage)
    {
        $user = $storage->getToken()->getUser();
        $otpAuthUrl = $authenticator->getQRContent($user);
        $qrcodeUrl = "https://chart.googleapis.com/chart?cht=qr&chs=300x300&chl=" . urlencode($otpAuthUrl);

        // Extract the secret from the otpAuthUrl
        $parsedUrl = parse_url($otpAuthUrl);
        parse_str($parsedUrl['query'], $queryParams);
        $secret = $queryParams['secret'] ?? '';

        return $this->render('Security/2fa_login.html.twig', [
            'qrcodeUrl' => $qrcodeUrl,
            'secret' => $secret // Pass only the secret to the template
        ]);
    }

    #[Route('/profile', name: 'app_profile')]
    public function profile(ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger,  UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UpdateFormType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $submittedPassword = $form->get('plainPassword')->getData();

            if (!$passwordHasher->isPasswordValid($user, $submittedPassword)) {
                $this->addFlash('error', 'The password you entered is incorrect.');
                return $this->redirectToRoute('app_profile');
            }


            $user->setModifiedAt(new \DateTimeImmutable());
            $profilePictureFile = $form->get('profilePicture')->getData();
            if ($profilePictureFile instanceof UploadedFile) {
                $originalFilename = pathinfo($profilePictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $profilePictureFile->guessExtension();

                $profilePictureFile->move(
                    $this->getParameter('profile_picture_directory'),
                    $newFilename
                );

                $user->setProfilePicture($newFilename);
            }

            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_profile');
        }

        return $this->render('security/profile.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    #[Route('/admin/users', name: 'admin_users')]
    public function users(UserRepository $repo): Response
    {

        $users = $repo->findAll();

        return $this->render('BackOffice/users.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/admin/users/delete/{id}', name: 'admin_delete_user')]
    public function deleteUser(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, int $id): Response
    {
        $user = $userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash('success', 'User deleted successfully');

        return $this->redirectToRoute('admin_users');
    }

    #[Route('/admin/users/stats', name: 'user_stats')]
    public function userStats(UserRepository $userRepository): Response
    {
        $userStats = $userRepository->countUsersByCreationDate();

        $labels = [];
        $data = [];
        foreach ($userStats as $stat) {
            $labels[] = $stat['createdAt'];
            $data[] = $stat['userCount'];
        }

        return $this->render('BackOffice/user_stats.html.twig', [
            'userStats' => $userStats,
            'labels' => $labels,
            'data' => $data,
        ]);
    }
}
