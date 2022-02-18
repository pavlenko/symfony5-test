<?php

namespace App\Controller;

use App\Repository\UriRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UriController extends AbstractController
{
    public function view(string $hash, UriRepository $repository): Response
    {
        $uri = $repository->findOneBy(['hash' => $hash]);
        if (!$uri) {
            throw new NotFoundHttpException('URI not found');//<-- Warn: for dev environment trace is shown
        }

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
