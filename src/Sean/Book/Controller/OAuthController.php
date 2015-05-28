<?php

namespace Sean\Book\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class OAuthController extends Controller
{
    public function clientAction()
    {
        $clientManager = $this->container->get('fos_oauth_server.client_manager.default');
        $client = $clientManager->createClient();
        $client->setRedirectUris(array('http://localhost:8000'));
        $client->setAllowedGrantTypes(array('token', 'password'));
        $clientManager->updateClient($client);
        
        return $this->render('BookBundle:OAuth:client_details.html.twig', array('oauth' => $client));

        //Redirect if providing an application access code
        /*return $this->redirect($this->generateUrl('fos_oauth_server_authorize', array(
            'client_id'     => $client->getPublicId(),
            'redirect_uri'  => 'http://localhost:8000',
            'response_type' => 'code'
        )));*/
    }
}