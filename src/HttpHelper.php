<?php
/**
 * Copyright (c) Padosoft.com 2016.
 */

namespace Padosoft\Workbench;

use Bitbucket\API\Authentication\Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;


class HttpHelper
{

    protected $httpclient;
    protected $response;
    
    public function __construct(Client $objguzzle)
    {
        $this->httpclient = $objguzzle;

    }


    private function requestRaw($method ,$uri = null, HeaderHttpHelper $objheader = null )
    {


        /*$headers = [
            'headers' => [
                'Authorization' => 'Basic YWxldmVudG86MTI5ODk1YWxl',
            ],
            'json' => ['name'=>'ciccio'],
        ];*/

        $response = $this->httpclient->request($method, $uri, $objheader->options);

        return $response;

    }

    public function request($method ,$uri = null, HeaderHttpHelper $objheader = null )
    {
        $response = null;
        try {

            $this->response = $this->requestRaw($method ,$uri , $objheader );
            $response = $this->response->getBody()->getContents();

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // code 422 se esiste repo
            throw new Exception($e->getMessage().' '.$e->getResponse().' '.$e->getRequest().' '.$e->getResponse()->getStatusCode(),$e->getCode(),$e);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            throw new Exception($e->getMessage().' '.$e->getResponse().' '.$e->getRequest().' '.$e->getResponse()->getStatusCode(),$e->getCode(),$e);
        }
        return $response;
    }

    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
        }
    }


}