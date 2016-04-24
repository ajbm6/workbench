<?php
/**
 * Copyright (c) Padosoft.com 2016.
 */

namespace Padosoft\Workbench;


class HeaderHttpHelper
{
    protected  $debug;
    protected  $options;
    protected  $connect_timeout;
    protected  $timeout;
    protected  $allow_redirects;
    protected  $allow_redirects__max;
    protected  $allow_redirects__strict;
    protected  $allow_redirects__referer;
    protected  $allow_redirects__protocols;
    protected  $allow_redirects__track_redirects;
    protected  $headers;
    protected  $headers__accept;
    protected  $headers__authorization;
    protected  $headers__authorization__method;
    protected  $headers__authorization__username;
    protected  $headers__authorization__password;
    protected  $json;
    protected  $multipart;
    protected  $name;
    //protected  $file;

    public function __construct()
    {
        $this->allow_redirects__max=3;
        //$this->addParam('allow_redirects__max');
        $this->allow_redirects__strict=true;
        $this->allow_redirects__protocols=['http', 'https'];
        $this->allow_redirects = [
                'max' => $this->allow_redirects__max,        // allow at most 'max' redirects.
                'strict' => $this->allow_redirects__strict,      // use "strict" RFC compliant redirects.
                'referer' => $this->allow_redirects__referer,      // add a Referer header
                'protocols' => $this->allow_redirects__protocols, // only allow http and https URLs
                'track_redirects' => $this->allow_redirects__track_redirects
            ] ;
        $this->connect_timeout = 20;
        $this->timeout = 30;
        $this->debug = false; //set to true to log into console output
        $this->file='';
        $this->headers__accept = 'application/json';
        $this->headers = [
            'Accept' => $this->headers__accept
        ];
        $this->headers__authorization__method='Basic';
        $this->headers__authorization__username='';
        $this->headers__authorization__password='';

        /*$this->multipart = [
            [
                'name' => 'lock',
                'contents' => fopen($this->file, 'r')
            ]
        ];*/
        /*
        $this->options = [
            //OPTIONS
            'allow_redirects' => $this->allow_redirects,
            'connect_timeout' => $this->connect_timeout,//Use 0 to wait connection indefinitely
            'timeout' => $this->timeout, //Use 0 to wait response indefinitely
            'debug' => $this->debug,
            //HEADERS
            'headers' =>  $this->headers,
            //UPLOAD FORM FILE
            //'multipart' => $this->multipart
        ];*/
    }


    public function __get($property) {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }

    public function __set($property, $value) {
        if (property_exists($this, $property)) {
            $this->$property = $value;
            $this->addParam($property);
        }
        return $this;
    }



    public function addParam($param)
    {
        $subparam = explode("__","$param");
        if(sizeof($subparam)==1) {
            $this->options[$param]=$this->$param;
        }

        if(sizeof($subparam)==2) {
            switch($subparam[0]) {
                //case 'authorization':
                //    $this->options[$subparam[0]] = $this->authorization__method .' '. base64_encode($this->authorization__username.':'.$this->authorization__password);
                //    break;
                default :
                    $this->options[$subparam[0]][$subparam[1]]=$this->$param;
                    break;
            }
        }

        if(sizeof($subparam)==3) {
            switch($subparam[1]) {
                case 'authorization':
                    $this->options[$subparam[0]][$subparam[1]] = $this->headers__authorization__method .' '. base64_encode($this->headers__authorization__username.':'.$this->headers__authorization__password);
                    break;
                default :
                    $this->options[$subparam[0]][$subparam[1]][$subparam[2]]=$this->$param;
                    break;
            }
        }


    }

    public function removeParam($param)
    {
        $subparam = explode("__","$param");
        if(sizeof($subparam)==1) {
            unset($this->options[$param]);
        }

        if(sizeof($subparam)==2) {
            switch($subparam[0]) {
                //case 'authorization':
                //    unset($this->options[$subparam[0]]);
                //    break;
                default :
                    unset($this->options[$subparam[0]][$subparam[1]]);
                    break;
            }
        }

        if(sizeof($subparam)==3) {
            switch($subparam[1]) {
                case 'authorization':
                    unset($this->options[$subparam[0]][$subparam[1]]);
                    break;
                default :
                    unset($this->options[$subparam[0]][$subparam[1]][$subparam[2]]);
                    break;
            }
        }
    }


}