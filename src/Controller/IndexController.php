<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="app")
     */
    public function index(): Response
    {
        $token = $this->get('security.token_storage')->getToken();

        $user = null;
        if ($token && is_object($token->getUser())) {
            $user = $token->getUser();
        }

        return $this->render('index.html.twig', ['user' => $user]);
    }
}