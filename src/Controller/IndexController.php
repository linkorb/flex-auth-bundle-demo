<?php

namespace App\Controller;


use App\FlexAuth\PersistAuthFlexTypeProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", methods={"GET", "POST"}, name="app")
     */
    public function index(Request $request, PersistAuthFlexTypeProvider $authFlexTypeProvider): Response
    {
        if ($request->isMethod("POST")) {
            $authFlexTypeProvider->set($request->get('type'));
        }

        $token = $this->get('security.token_storage')->getToken();

        $user = null;
        if ($token && is_object($token->getUser())) {
            $user = $token->getUser();
        }

        return $this->render('index.html.twig', [
            'user' => $user,
            'types' => $authFlexTypeProvider->types,
            'selectedType' => $authFlexTypeProvider->provide(),
            'isJWT' => $request->query->get('jwt')
        ]);
    }
}