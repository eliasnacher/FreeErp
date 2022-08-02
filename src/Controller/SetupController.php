<?php

namespace App\Controller;

use Exception;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SetupController extends AbstractController
{
    #[Route('/setup', name: 'app_setup')]
    public function index(): Response
    {
        return $this->render('setup/index.html.twig', [
            'controller_name' => 'SetupController',
        ]);
    }

    #[Route('/setup/save', name: 'app_setup_save', methods: ['POST'])]
    public function save(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher)
    {
        // Check if admin user alredy exist.
        $adminUser = $userRepository->findOneBy(['username' => 'admin']);
        if ($adminUser) throw new Exception('Error: Admin user alredy exist');

        // Create Admin user with password sent.
        $adminUser = new User ();
        $adminUser->setUsername('admin');
        $adminUser->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $hashedPassword = $passwordHasher->hashPassword(
            $adminUser,
            $request->request->get('password')
        );
        $adminUser->setPassword($hashedPassword);
        $userRepository->add($adminUser, true);

        // Redirect HomePage
        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
    }
}
