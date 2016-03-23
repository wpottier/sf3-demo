<?php

namespace AppBundle\Mailer;

use Symfony\Bundle\TwigBundle\TwigEngine;

class NotificationMailer
{
    private $senderName;

    private $senderEmail;

    /** @var TwigEngine */
    private $templating;

    /** @var \Swift_Mailer */
    private $mailer;

    public function __construct($senderName, $senderEmail, TwigEngine $templating, \Swift_Mailer $mailer)
    {
        $this->senderName = $senderName;
        $this->senderEmail = $senderEmail;
        $this->templating = $templating;
        $this->mailer = $mailer;
    }

    public function sendNewTrackInPlaylist($to, $trackTitle, $playlistName)
    {
        $message = \Swift_Message::newInstance(sprintf('Une piste a été ajoutée à votre playliste %s', $playlistName));
        $message
            ->setTo($to)
            ->setCharset('utf-8')
            ->setFrom($this->senderEmail, $this->senderName);
        $message->addPart($this->templating->render('AppBundle:Mailer/Notification:new-track-in-playlist.html.twig', [

        ]), 'text/html', 'utf-8');

        $this->mailer->send($message);
    }
}