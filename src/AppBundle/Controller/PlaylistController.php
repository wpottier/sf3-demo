<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Playlist;
use AppBundle\Entity\User;
use AppBundle\Form\PlaylistType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PlaylistController extends Controller
{
    public function indexAction()
    {
        $ownPlaylists = null;
        if ($this->getUser() instanceof User) {
            $ownPlaylists = $this->get('app.repository.playlist')->findOwn($this->getUser());
        }

        return $this->render('AppBundle:Playlist:index.html.twig', [
            'publicPlaylists' => $this->get('app.repository.playlist')->findPublic(),
            'ownPlaylists' => $ownPlaylists
        ]);
    }

    public function viewAction($id)
    {
        $playlist = $this->getDoctrine()->getRepository('AppBundle:Playlist')->find($id);

        if (!$playlist) {
            throw $this->createNotFoundException();
        }

        return $this->render('AppBundle:Playlist:view.html.twig', [
            'playlist' => $playlist,
        ]);
    }

    public function editAction(Request $request, $id = null)
    {
        $playlist = null;

        if (!is_null($id)) {
            $playlist = $this->getDoctrine()->getRepository('AppBundle:Playlist')->find($id);

            if (!$playlist) {
                throw $this->createNotFoundException();
            }
        }

        if (!$playlist) {
            $playlist = new Playlist($this->getUser());
        }

        $form = $this->createForm(PlaylistType::class, $playlist);

        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if (is_null($id)) {
                $em->persist($playlist);
            }

            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('AppBundle:Playlist:edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
