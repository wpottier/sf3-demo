<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Track;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TrackController extends Controller
{
    public function indexAction(Request $request)
    {
        $pager = new Pagerfanta($this->get('app.repository.track')->findAllWithArtistPagerAware());
        $pager->setCurrentPage($request->query->get('page', 1));

        return $this->render('AppBundle:Track:index.html.twig', [
            'tracks' => $pager,
        ]);
    }

    public function editAction(Request $request, $id = null)
    {
        $track = null;

        if (!is_null($id)) {
            $track = $this->get('app.repository.track')->find($id);

            if (!$track) {
                throw $this->createNotFoundException();
            }
        }

        if (!$track) {
            $track = new Track();
        }

        $form = $this->createForm(\AppBundle\Form\TrackType::class, $track);

        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if (is_null($id)) {
                $em->persist($track);
            }

            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('AppBundle:Track:edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}