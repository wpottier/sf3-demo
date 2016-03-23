<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        if ($form->handleRequest($request)->isValid()) {
            $encoder = $this->get('security.encoder_factory')
                ->getEncoder($user);

            $user->setPassword(
                $encoder->encodePassword(
                    $user->getPlainPassword(),
                    null
                )
            );
            $user->setRoles(['ROLE_USER']);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Youpi !');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('AppBundle:Security:register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}