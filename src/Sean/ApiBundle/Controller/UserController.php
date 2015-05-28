<?php

namespace Sean\ApiBundle\Controller;
    
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;

class UserController extends FOSRestController
{
    public function getUserAction()
    {
        $user = $this->getUser();
        return $this->handleView($this->view($user));
    }
}