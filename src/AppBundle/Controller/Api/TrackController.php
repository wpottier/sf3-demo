<?php

namespace AppBundle\Controller\Api;

use AppBundle\Form\TrackType;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackController extends FOSRestController
{
    public function indexAction()
    {
        $tracks = $this->get('app.repository.track')->findAll();

        return $this->handleView($this->view($tracks));
    }

    public function editAction(Request $request, $id)
    {
        $track = $this->get('app.repository.track')->find($id);

        if (!$track) {
            throw $this->createNotFoundException();
        }

        $form = $this->get('form.factory')->createNamed('', TrackType::class, $track, [
            'method' => Request::METHOD_PATCH,
            'csrf_protection' => false,
        ]);

        if ($request->isMethod(Request::METHOD_PATCH)) {
            $form->submit($request->request->all(), false);
            if ($form->isValid()) {
                $this->get('doctrine.orm.entity_manager')->flush();

                return $this->handleView($this->view($track, Response::HTTP_OK));
            }
        }

        return $this->handleView($this->view($form, Response::HTTP_BAD_REQUEST));
    }
}