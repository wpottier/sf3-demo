<?php

namespace AppBundle\DataCollector;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class SpotifyApiCollector extends DataCollector
{
    public function getName()
    {
        return 'spotify_api';
    }

    public function collect(Request $request, Response $response, \Exception $exception = null)
    {
        // Nothing to do
    }

    public function onRequest($uri)
    {
        if (!array_key_exists('calls',  $this->data)) {
            $this->data['calls'] = [];
        }

        $this->data['calls'][] = [
            'uri' => $uri,
        ];
    }

    public function getCalls()
    {
        if (!array_key_exists('calls',  $this->data)) {
            return [];
        }

        return $this->data['calls'];
    }
}