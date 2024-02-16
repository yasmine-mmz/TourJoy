<?php

namespace App\Controller;

use App\Form\UpdateFormType;
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




class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

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
    public function check2fa(GoogleAuthenticatorInterface $authentificator, TokenStorageInterface $storage)
    {
        $code = $authentificator->getQRContent($storage->getToken()->getUser());
        $qrcode = "https://chart.googleapis.com/chart?cht=qr&chs=300x300&chl=" . $code;
        return $this->render('Security/2fa_login.html.twig', [
            'qrcode' => $qrcode
        ]);
    }
    #[Route('/profile', name: 'app_profile')]
    public function profile(ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UpdateFormType::class, $user);

        $form->handleRequest($request);

        
        

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setModifiedAt(new \DateTimeImmutable());
            $profilePictureFile = $form->get('profilePicture')->getData();
            if ($profilePictureFile instanceof UploadedFile) {
                $originalFilename = pathinfo($profilePictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$profilePictureFile->guessExtension();

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
}
