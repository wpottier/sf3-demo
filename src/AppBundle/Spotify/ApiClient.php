<?php

namespace AppBundle\Spotify;

use GuzzleHttp\Client;

class ApiClient
{
    private $guzzleClient;

    private $spotifyApiId;

    private $spotifyApiSecret;

    private $spotifyAccessToken;

    public function __construct(Client $guzzleClient, $spotifyApiId, $spotifyApiSecret)
    {
        $this->guzzleClient = $guzzleClient;
        $this->spotifyApiId = $spotifyApiId;
        $this->spotifyApiSecret = $spotifyApiSecret;
    }

    public function search($query, $type)
    {
        $response = $this->guzzleClient->get(
            $url = sprintf('https://api.spotify.com/v1/search?q=%s&type=%s', $query, $type),
            [
                'verify' => false,
                'headers' => [
                    'Authorization' => sprintf('Bearer %s', $this->grantClientCredentials())
                ]
            ]
        );

        return  json_decode($response->getBody()->getContents());
    }

    public function populateTrackInfo($trackTitle, $artistName)
    {
        $spotifyResult = $this->search(
            sprintf('track:%s%%20artist:%s', $trackTitle, $artistName),
            'track'
        );

        return $spotifyResult->tracks;
    }

    protected function grantClientCredentials()
    {
        if (!$this->spotifyAccessToken) {
            $response = $this->guzzleClient->post('https://accounts.spotify.com/api/token', [
                'form_params' => [
                    'grant_type' => 'client_credentials'
                ],
                'auth' => [
                    $this->spotifyApiId,
                    $this->spotifyApiSecret
                ]
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);
            $this->spotifyAccessToken = $responseData['access_token'];
        }

        return $this->spotifyAccessToken;
    }
}