<?php

namespace App\Controller;

use App\Entity\Uri;
use App\Form\UriForm;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGenerator;

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

    public function edit(ManagerRegistry $registry, UrlGenerator $urlGenerator): Response
    {
        $uri = new Uri();

        $form = $this->createForm(UriForm::class, $uri);
        if ($form->isSubmitted() && $form->isValid()) {
            //TODO generate and set hash
            $hash = '123456';
            $uri->setHash($hash);

            $manager = $registry->getManager();
            $manager->persist($uri);
            $manager->flush();

            //TODO show success message with short link
            $this->addFlash('success', $urlGenerator->generate('app_uri_view', ['hash' => $hash]));
            return $this->redirectToRoute('app_uri_success');
        }

        return $this->render('uri/edit.html.twig', ['form' => $form]);
    }
}
