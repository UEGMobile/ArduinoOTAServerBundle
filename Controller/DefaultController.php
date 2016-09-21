<?php

namespace UEGMobile\ArduinoOTAServerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('UEGMobileArduinoOTAServerBundle:Default:index.html.twig', array('name' => $name));
    }
}
