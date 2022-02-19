<?php

namespace App\Controller;

use App\Entity\Uri;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UriController extends AbstractController
{
    public function view(string $hash, ManagerRegistry $registry): Response
    {
        $manager = $registry->getManager();
        $uri = $manager->getRepository(Uri::class)->findOneBy(['hash' => $hash]);
        if (!$uri) {
            throw new NotFoundHttpException('URI not found');//<-- Warn: for dev environment trace is shown
        }
        if ($uri->getMaxRedirects() > 0 && $uri->getMaxRedirects() <= $uri->getNumRedirects()) {
            throw new NotFoundHttpException('URI not found');
        }
        if ($uri->getExpiredAt() < new \DateTimeImmutable()) {
            throw new NotFoundHttpException('URI not found');
        }

        $uri->setNumRedirects($uri->getNumRedirects() + 1);

        $manager->persist($uri);
        $manager->flush();

        return $this->redirect($uri->getUri());
    }

    public function edit(): Response
    {
        //TODO on success show generated link
        return $this->render('uri/edit.html.twig');
    }
}
