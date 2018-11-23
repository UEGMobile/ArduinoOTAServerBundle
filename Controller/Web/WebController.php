<?php

namespace UEGMobile\ArduinoOTAServerBundle\Controller\Web;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class WebController extends Controller
{
    /**
     * @Route("/web/programs", defaults={"_format" = "html"})
     */
    public function programsAction(Request $request)
    {
        $this->container->get('logger')->debug('Get Programs');

        return $this->render(
            '@UEGMobileArduinoOTAServer/programs.html.twig', 
            array(
                'number' => 1
            )
        );
    }

    /**
     * @Route("/web/binaries", defaults={"_format" = "html"})
     */
    public function binariesAction(Request $request)
    {
        $this->container->get('logger')->debug('Get Binaries');

        return $this->render(
            '@UEGMobileArduinoOTAServer/binaries.html.twig', 
            array(
                'number' => 1
            )
        );
    }


    /**
     * @Route("/web/devices", defaults={"_format" = "html"})
     */
    public function devicesAction(Request $request)
    {
        $this->container->get('logger')->debug('Get Binaries');

        return $this->render(
            '@UEGMobileArduinoOTAServer/devices.html.twig', 
            array(
                'number' => 1
            )
        );
    }
}
