<?php

namespace AppBundle\Spotify;

use GuzzleHttp\Client;

class ApiClient
{
    private $guzzleClient;

    public function __construct(Client $guzzleClient)
    {
        $this->guzzleClient = $guzzleClient;
    }

    public function search($query, $track)
    {
        $response = $this->guzzleClient->get(
            sprintf('https://api.spotify.com/v1/search?q=%s&type=%s', $query, $track)
        );


        return json_decode($response->getBody()->getContents());
    }

    public function populateTrackInfo($trackTitle, $artistName)
    {
        $spotifyResult = $this->search(
            sprintf('track:%s%%20artist:%s', $trackTitle, $artistName),
            'track'
        );

        return $spotifyResult->tracks;
    }
}