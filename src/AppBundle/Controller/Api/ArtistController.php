<?php

namespace AppBundle\Controller\Api;

use AppBundle\Entity\Artist;
use AppBundle\Form\ArtistType;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ArtistController extends FOSRestController
{
    public function indexAction()
    {
        $artists = $this->get('app.repository.artist')->findAll();

        $view = $this->view($artists);
        $view->getContext()->setGroups(['Default']);

        return $this->handleView($view);
    }

    public function viewAction($id)
    {
        $artist = $this->get('app.repository.artist')->find($id);

        if (!$artist) {
            throw $this->createNotFoundException();
        }

        $view = $this->view($artist);
        $view->getContext()->setGroups(['Default', 'Full']);

        return $this->handleView($view);
    }

    public function newAction(Request $request)
    {
        $form = $this->get('form.factory')->createNamed(
            '',
            ArtistType::class,
            $artist = new Artist(),
            [
                'csrf_protection' => false,
            ]
        );

        if ($form->handleRequest($request)->isValid()) {
            $this->get('doctrine.orm.entity_manager')->persist($artist);
            $this->get('doctrine.orm.entity_manager')->flush();

            return $this->handleView($this->view(
                null,
                Response::HTTP_CREATED,
                [
                    'Location' => $this->generateUrl('app_api_artist_view', [
                        'id' => $artist->getId()
                    ], UrlGeneratorInterface::ABSOLUTE_URL)
                ]
            ));
        }

        return $this->handleView($this->view(
            $form,
            Response::HTTP_BAD_REQUEST
        ));
    }
}