<?php

namespace App\Controller;

use App\Entity\Uri;
use App\Form\UriForm;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
        if ($uri->getExpiredAt() < new \DateTime()) {
            throw new NotFoundHttpException('URI not found');
        }

        $uri->setNumRedirects($uri->getNumRedirects() + 1);

        $manager->persist($uri);
        $manager->flush();

        return $this->redirect($uri->getUri());
    }

    public function edit(Request $request, ManagerRegistry $registry, UrlGeneratorInterface $urlGenerator): Response
    {
        $uri = new Uri();
        $uri->setCreatedAt(new \DateTime());
        $uri->setNumRedirects(0);

        $form = $this->createForm(UriForm::class, $uri);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            //TODO generate and set hash
            $hash = '12345678';
            $uri->setHash($hash);
            //dd($uri);
            $manager = $registry->getManager();
            $manager->persist($uri);
            $manager->flush();

            //TODO show success message with short link
            $this->addFlash('success', $urlGenerator->generate('app_uri_view', ['hash' => $hash]));
            return $this->redirectToRoute('app_uri_success');
        }

        return $this->render('uri/edit.html.twig', ['form' => $form->createView()]);
    }

    public function success(): Response
    {
        return $this->render('uri/success.html.twig');
    }
}
