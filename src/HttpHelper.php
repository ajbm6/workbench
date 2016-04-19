<?php
/**
 * Copyright (c) Padosoft.com 2016.
 */

namespace Padosoft\Workbench;

use GuzzleHttp\Client;


class HttpHelper
{

    protected $guzzle;
    
    public function __construct(Client $objguzzle)
    {
        $this->guzzle = $objguzzle;

    }



    public function getSensiolabVulnerabilties($fileLock)
    {


        $debug = false;//set to true to log into console output
        $headers = [
            //OPTIONS
            'allow_redirects' => [
                'max' => 3,        // allow at most 10 redirects.
                'strict' => true,      // use "strict" RFC compliant redirects.
                'referer' => true,      // add a Referer header
                'protocols' => ['http', 'https'], // only allow http and https URLs
                'track_redirects' => false
            ],
            'connect_timeout' => 20,//Use 0 to wait connection indefinitely
            'timeout' => 30, //Use 0 to wait response indefinitely
            'debug' => $debug,
            //HEADERS
            'headers' => [
                'Accept' => 'application/json'
            ],
            //UPLOAD FORM FILE
            'multipart' => [
                [
                    'name' => 'lock',
                    'contents' => fopen($fileLock, 'r')
                ]
            ]
        ];
        $response = null;

        try {
            $iResponse = $this->guzzle->request('POST', 'https://security.sensiolabs.org/check_lock', $headers);
            $responseBody = $iResponse->getBody()->getContents();
            $response = json_decode($responseBody, true);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $this->command->error("ClientException!\nMessage: " . $e->getMessage());
            $colorTag = $this->getColorTagForStatusCode($e->getResponse()->getStatusCode());
            $this->command->line("HTTP StatusCode: <{$colorTag}>" . $e->getResponse()->getStatusCode() . "<{$colorTag}>");
            $this->printMessage($e->getResponse());
            $this->printMessage($e->getRequest());
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $this->command->error("RequestException!\nMessage: " . $e->getMessage());
            $this->printMessage($e->getRequest());
            if ($e->hasResponse()) {
                $colorTag = $this->getColorTagForStatusCode($e->getResponse()->getStatusCode());
                $this->command->line("HTTP StatusCode: <{$colorTag}>" . $e->getResponse()->getStatusCode() . "<{$colorTag}>");
                $this->printMessage($e->getResponse());
            }
        }
        return $response;
    }

}