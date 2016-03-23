<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('AppBundle:Default:index.html.twig', [
            'playlists' => $this->get('app.repository.playlist')->findAll()
        ]);
    }

    public function newTrackAction(Request $request)
    {
        $track = new \AppBundle\Entity\Track();
        $form = $this->createForm(\AppBundle\Form\TrackType::class, $track);

        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($track);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('AppBundle:Default:new-track.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
