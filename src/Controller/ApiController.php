<?php

namespace App\Controller;

use FlexAuth\Type\JWT\JWTTokenAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\User;

/**
 * Class ApiController
 * @author Aleksandr Arofikin <sashaaro@gmail.com>
 *
 * @Route("/api")
 */
class ApiController extends AbstractController
{
    /**
     * @Route("/login", methods={"POST"}, name="api_login")
     */
    public function loginAction(Request $request, JWTTokenAuthenticator $JWTTokenAuthenticator) {
        $content = $request->getContent();
        $content = json_decode($content);

        /** @var JWTTokenAuthenticator $JWTTokenAuthenticator */
        $user = new User($content->username, null, ['ROLE_USER']);
        $token = $JWTTokenAuthenticator->createTokenFromUser($user);

        return $this->json(['token' => $token]);
    }
}