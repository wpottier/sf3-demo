<?php

namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class AddToPlaylistEvent extends Event
{
    private $playlist;

    private $track;

    /**
     * AddToPlaylistEvent constructor.
     *
     * @param $playlist
     * @param $track
     */
    public function __construct($playlist, $track)
    {
        $this->playlist = $playlist;
        $this->track = $track;
    }

    /**
     * @return mixed
     */
    public function getPlaylist()
    {
        return $this->playlist;
    }

    /**
     * @return mixed
     */
    public function getTrack()
    {
        return $this->track;
    }


}