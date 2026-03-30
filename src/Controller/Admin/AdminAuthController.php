<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminAuthController extends AbstractController
{
    #[Route('/admin/login', name: 'app_admin_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Si l'utilisateur est déjà connecté, rediriger vers le dashboard
        if ($this->getUser()) {
            return $this->redirectToRoute('app_admin_products');
        }

        // Récupérer l'erreur de connexion s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();

        // Dernier nom d'utilisateur saisi
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('admin/auth/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/admin/logout', name: 'app_admin_logout', methods: ['GET'])]
    public function logout(): void
    {
        // Cette méthode ne sera jamais exécutée,
        // le firewall gère la déconnexion automatiquement
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
