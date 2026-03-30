<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/api')]
class AuthController extends AbstractController
{
    /**
     * This route is handled by the json_login firewall.
     * It authenticates the user and returns a JWT token via the success handler.
     */
    #[Route('/login_check', name: 'api_login_check', methods: ['POST'])]
    public function loginCheck(): Response
    {
        // This method will never be executed - the firewall intercepts the request
        // and the success handler returns the JWT token automatically.
        return new Response('This route is handled by the firewall', 200);
    }

    /**
     * Get the currently authenticated user's info via JWT.
     */
    #[Route('/me', name: 'api_me', methods: ['GET'])]
    public function getCurrentUser(): JsonResponse
    {
        $user = $this->getUser();

        if (!$user instanceof UserInterface) {
            return new JsonResponse(['error' => 'Not authenticated'], 401);
        }

        return new JsonResponse([
            'id' => $user->getId(),
            'email' => $user->getUserIdentifier(),
            'roles' => $user->getRoles(),
        ]);
    }

    /**
     * Logout endpoint for API - invalidate the token on client side.
     * JWT tokens are stateless, so server-side logout just confirms success.
     */
    #[Route('/logout', name: 'api_logout', methods: ['POST'])]
    public function logout(): JsonResponse
    {
        // JWT is stateless - the client should remove the token
        return new JsonResponse(['message' => 'Logged out successfully']);
    }
}
