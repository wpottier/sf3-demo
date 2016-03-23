<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Artist;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ArtistController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $pager = new Pagerfanta($this->get('app.repository.artist')->findAllWithTracksPagerAware());
        $pager->setCurrentPage($request->request->get('page', 1));

        return $this->render('AppBundle:Artist:index.html.twig', [
            'artists' => $pager,
        ]);
    }

    public function editAction(Request $request, $id = null)
    {
        $artist = null;

        if (!is_null($id)) {
            $artist = $this->get('app.repository.artist')->find($id);

            if (!$artist) {
                throw $this->createNotFoundException();
            }
        }

        if (!$artist) {
            $artist = new Artist();
        }

        $form = $this->createForm(\AppBundle\Form\ArtistType::class, $artist);

        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if (is_null($id)) {
                $em->persist($artist);
            }

            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('AppBundle:Artist:edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}