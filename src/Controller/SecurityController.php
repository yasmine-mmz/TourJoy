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
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use App\Entity\User;




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
            'secret' => $secret
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
    public function deleteUser(EntityManagerInterface $entityManager, UserRepository $userRepository, int $id): Response
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
        // Get user statistics from the repository
        $userStats = $userRepository->countUsersByCreationDate();

        // Assume the first and last statistics have the earliest and latest dates
        $startDate = new \DateTime($userStats[0]['createdAt']); // Use the actual date from your stats
        $endDate = new \DateTime(end($userStats)['createdAt']); // Use the actual date from your stats
        $endDate = $endDate->modify('+1 day'); // Include the end date in the period


        $interval = new \DateInterval('P1D');


        $period = new \DatePeriod($startDate, $interval, $endDate);


        $labels = [];
        $data = [];
        foreach ($period as $date) {
            $dateStr = $date->format('Y-m-d');
            $labels[] = $dateStr;
            $data[$dateStr] = 0;
        }


        foreach ($userStats as $stat) {
            $dateStr = new \DateTime($stat['createdAt']);
            $dateStr = $dateStr->format('Y-m-d');
            if (array_key_exists($dateStr, $data)) {
                $data[$dateStr] = $stat['userCount'];
            }
        }


        $data = array_values($data);

        return $this->render('BackOffice/user_stats.html.twig', [
            'labels' => $labels,
            'data' => $data,
        ]);
    }

    #[Route('/admin/users/ban/{id}', name: 'admin_ban_user')]
    public function banUser(EntityManagerInterface $entityManager, UserRepository $userRepository, int $id): Response
    {
        $user = $userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $user->setIsBanned(true);
        $entityManager->flush();

        $this->addFlash('success', 'User banned successfully');

        return $this->redirectToRoute('admin_users');
    }

    #[Route('/admin/users/revoke/{id}', name: 'admin_revoke_user')]
    public function revokeBanUser(EntityManagerInterface $entityManager, UserRepository $userRepository, int $id): Response
    {
        $user = $userRepository->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $user->setIsBanned(false);
        $entityManager->flush();

        $this->addFlash('success', 'User unbanned successfully');

        return $this->redirectToRoute('admin_users');
    }

    #[Route('/connect/google', name: 'connect_google_start')]
    public function connectAction(ClientRegistry $clientRegistry)
    {
        if($this->getUser()){
            return $this->redirectToRoute('app_test');
        }
        
        return $clientRegistry
            ->getClient('google_main')
            ->redirect([
                'email'
            ]);
    }
    
    #[Route('/connect/google/check', name:'connect_google_check')]
    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry)
    {
        /** @var \KnpU\OAuth2ClientBundle\Client\Provider\GoogleClient $client */
        $client = $clientRegistry->getClient('google_main');

        try {
            /** @var \League\OAuth2\Client\Provider\GoogleUser $user */
            $user = $client->fetchUser();
            var_dump($user); die;
        } catch (IdentityProviderException $e) {
            var_dump($e->getMessage()); die;
        }
    }


    #[Route('/admin/users/export-csv', name: 'admin_export_users_csv')]
public function exportUsersCsv(EntityManagerInterface $entityManager): Response
{
    $users = $entityManager->getRepository(User::class)->findAll();

    $output = fopen('php://temp', 'w+'); // File pointer to 'temp' memory

    // Add CSV headers
    fputcsv($output, ['ID', 'Email', 'First Name', 'Last Name', 'Phone Number', 'Country', 'Created At', 'Modified At', 'isVerified', 'isBanned', 'Roles']);

    // Add user data
    foreach ($users as $user) {
        fputcsv($output, [
            $user->getId(),
            $user->getEmail(),
            $user->getFirstName(),
            $user->getLastName(),
            $user->getPhoneNumber(),
            $user->getCountry(),
            $user->getCreatedAt()->format('Y-m-d H:i:s'),
            $user->getModifiedAt() ? $user->getModifiedAt()->format('Y-m-d H:i:s') : '',
            $user->isVerified() ? 'Yes' : 'No',
            $user->getIsBanned() ? 'Yes' : 'No',
            implode(', ', $user->getRoles())
        ]);
    }

    rewind($output); // Set the pointer back to the start of the file

    $csv = stream_get_contents($output); // Get the contents of 'temp' memory
    fclose($output); // Close the "file" pointer

    $response = new Response($csv);

    $disposition = $response->headers->makeDisposition(
        ResponseHeaderBag::DISPOSITION_ATTACHMENT,
        'users.csv'
    );

    $response->headers->set('Content-Disposition', $disposition);
    $response->headers->set('Content-Type', 'text/csv');

    return $response;
}

}
