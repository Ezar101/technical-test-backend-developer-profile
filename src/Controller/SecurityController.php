<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SecurityController extends AbstractController
{
    #[Route('%app.api_route%/login', name: 'api_login', methods: ['POST'])]
    public function login(): Response
    {
        $user = $this->getUser();

        return $this->json([
            'username' => $user->getUserIdentifier(),
            'roles'    => $user->getRoles()
        ]);
    }

    #[Route('%app.api_route%/logout', name: 'api_logout', methods: ['POST'])]
    public function logout(): void
    {
        throw new \Exception('This should never be reached!');
    }
}
