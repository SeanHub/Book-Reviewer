<?php

namespace Sean\ExternalApiBundle\Controller;
    
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use GuzzleHttp\Client;

class ExternalApiController extends FOSRestController
{
    public function getBookAction($isbn)
    {
        $client = new Client();
        $response = $client->get('https://www.googleapis.com/books/v1/volumes?q=isbn:'.$isbn);
        return $this->handleView($this->view($response->json()));
    }
}