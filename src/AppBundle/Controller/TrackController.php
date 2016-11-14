<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Playlist;
use AppBundle\Entity\Track;
use AppBundle\Form\AddToPlaylistType;
use AppBundle\Form\TrackType;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TrackController extends Controller
{
    public function indexAction(Request $request)
    {
        $pager = new Pagerfanta(
            $this->getDoctrine()->getRepository('AppBundle:Track')->findAllWithArtistPagerAware()
        );
        $pager->setCurrentPage($request->query->get('page', 1));

        return $this->render('AppBundle:Track:index.html.twig', [
            'tracks' => $pager,
        ]);
    }

    public function viewAction($id)
    {
        $track = $this->getDoctrine()->getRepository('AppBundle:Track')->find($id);

        if (!$track) {
            throw $this->createNotFoundException();
        }

        $spotifyData = $this->get('app.spotify.api')->populateTrackInfo(
            $track->getName(),
            $track->getArtist()->getName()
        );

        return $this->render('AppBundle:Track:view.html.twig', [
            'track' => $track,
            'spotifyData' => $spotifyData,
        ]);
    }

    public function editAction(Request $request, $id = null)
    {
        $track = null;

        if (!is_null($id)) {
            $track = $this->getDoctrine()->getRepository('AppBundle:Track')->find($id);

            if (!$track) {
                throw $this->createNotFoundException();
            }
        }

        if (!$track) {
            $track = new Track();
        }

        $form = $this->createForm(TrackType::class, $track);

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

    public function addToPlaylistAction(Request $request, $id)
    {
        $track = $this->getDoctrine()->getRepository('AppBundle:Track')->find($id);

        if (!$track) {
            throw $this->createNotFoundException();
        }

        $form = $this->createForm(AddToPlaylistType::class, null, [
            'action' => $request->getPathInfo()
        ]);

        if ($form->handleRequest($request)->isValid()) {

            /** @var Playlist $playlist */
            $playlist = $form->getData()['playlist'];
            $playlist->addTrack($track);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('app_playlist_view', [
                'id' => $playlist->getId(),
            ]);
        }

        return $this->render('AppBundle:Track:addToPlaylist.html.twig', [
            'track' => $track,
            'form' => $form->createView(),
        ]);
    }
}