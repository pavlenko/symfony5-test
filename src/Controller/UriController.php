<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UriController extends AbstractController
{
    public function view(string $hash): Response
    {
        throw new NotFoundHttpException();//<-- Warn: for dev environment trace is shown
        //TODO check exists -> 404
        //TODO check redirects -> 404
        //TODO check expired -> 404
        //TODO redirect
        return new Response('VIEW');
    }

    public function edit(): Response
    {
        //TODO on success show generated link
        return $this->render('uri/edit.html.twig');
    }
}
