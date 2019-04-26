<?php

namespace App\Controller;


use App\FlexAuth\PersistFlexAuthTypeProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", methods={"GET", "POST"}, name="app")
     */
    public function index(Request $request, PersistFlexAuthTypeProvider $flexAuthTypeProvider): Response
    {
        if ($request->isMethod("POST")) {
            $flexAuthTypeProvider->set($type = $request->get('type'), $request->get('encoder'));
        }

        $token = $this->get('security.token_storage')->getToken();

        $user = null;
        if ($token && is_object($token->getUser())) {
            $user = $token->getUser();
        }

        return $this->render('index.html.twig', [
            'user' => $user,
            'types' => $flexAuthTypeProvider->types,
            'selectedType' => $flexAuthTypeProvider->provide(),
            'isJWT' => $request->query->get('jwt'),
            'encoders' => [
                'plaintext',
                'pbkdf2',
                'argon2i',
                'plain'
            ]
        ]);
    }
}