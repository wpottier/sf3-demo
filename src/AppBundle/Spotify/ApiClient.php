<?php

namespace AppBundle\Spotify;

use AppBundle\DataCollector\SpotifyApiCollector;
use GuzzleHttp\Client;
use Symfony\Component\Stopwatch\Stopwatch;

class ApiClient
{
    private $guzzleClient;

    private $spotifyApiCollector;

    /** @var Stopwatch */
    private $stopwatch;

    public function __construct(Client $guzzleClient, SpotifyApiCollector $spotifyApiCollector = null)
    {
        $this->guzzleClient = $guzzleClient;
        $this->spotifyApiCollector = $spotifyApiCollector;
    }

    public function setStopwatch(Stopwatch $stopwatch = null)
    {
        $this->stopwatch = $stopwatch;
    }

    public function search($query, $track)
    {
        if ($this->stopwatch) {
            $this->stopwatch->start('guzzle');
        }

        $response = $this->guzzleClient->get(
            $url = sprintf('https://api.spotify.com/v1/search?q=%s&type=%s', $query, $track),
            ['verify' => false]
        );
        $this->spotifyApiCollector->onRequest($url);

        if ($this->stopwatch) {
            $this->stopwatch->stop('guzzle');
            $this->stopwatch->start('json_decode');
        }

        $result = json_decode($response->getBody()->getContents());

        if ($this->stopwatch) {
            $this->stopwatch->stop('json_decode');
        }

        return $result;
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