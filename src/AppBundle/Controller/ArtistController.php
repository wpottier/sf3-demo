<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Artist;
use AppBundle\Form\ArtistType;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ArtistController extends Controller
{
    /**
     * @param Request $request
     *
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
        $deleteForm = null;

        if (!is_null($id)) {
            /** @var Artist $artist */
            $artist = $this->getDoctrine()->getRepository('AppBundle:Artist')->find($id);

            if (!$artist) {
                throw $this->createNotFoundException();
            }

            $deleteForm = $this->createDeleteForm($artist);
        }

        if (!$artist) {
            $artist = new Artist();
        }

        $form = $this->createForm(ArtistType::class, $artist);

        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();

            if (is_null($id)) {
                $em->persist($artist);
            }

            $em->flush();

            return $this->redirectToRoute('app_artist_index');
        }

        return $this->render('AppBundle:Artist:edit.html.twig', [
            'form' => $form->createView(),
            'deleteForm' => $deleteForm ? $deleteForm->createView() : null,
        ]);
    }

    public function deleteAction(Request $request, $id)
    {
        $artist = $this->getDoctrine()->getRepository('AppBundle:Artist')->find($id);

        if (!$artist) {
            throw $this->createNotFoundException();
        }

        $deleteForm = $this->createDeleteForm($artist);
        if ($deleteForm->handleRequest($request)->isValid()) {
            // Suppression
            $em = $this->getDoctrine()->getManager();
            $em->remove($artist);
            $em->flush();

            $this->addFlash('success', 'L\'artiste a été supprimé');
        }

        return $this->redirectToRoute('app_artist_index');
    }

    protected function createDeleteForm(Artist $artist)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('app_artist_delete', ['id' => $artist->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}