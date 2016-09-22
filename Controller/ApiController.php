<?php

namespace UEGMobile\ArduinoOTAServerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

//use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use FOS\RestBundle\View\View,
    FOS\RestBundle\Controller\Annotations\Get,
    FOS\RestBundle\Controller\Annotations\Put,
    FOS\RestBundle\Controller\Annotations\Post,
    FOS\RestBundle\Controller\Annotations\Delete,
    FOS\RestBundle\Controller\Annotations\Route;


class ApiController extends Controller
{
    /**
     * @Get("/updateBinary")
     * @return View
     */
    public function getUpdateBinaryAction()
    {
        
        $view = View::create();
        $request = $this->container->get('request');
        
        $this->container->get('logger')->debug(json_encode($request->headers->all()));
                      
        $this->container->get('logger')->debug($request->headers->get('x-esp8266-ap-mac'));
        if(!$request->headers->get('x-esp8266-sta-mac') ||
           !$request->headers->get('x-esp8266-ap-mac') ||
           !$request->headers->get('x-esp8266-free-space') ||
           !$request->headers->get('x-esp8266-sketch-size') ||
           !$request->headers->get('x-esp8266-chip-size') ||
           !$request->headers->get('x-esp8266-sdk-version') ||
           !$request->headers->get('x-esp8266-version')
        ){
            $this->container->get('logger')->debug('Only for ESP8266 updater!');
            $view->setStatusCode(403);
            $view->setData('Only for ESP8266 updater!');
            return $view;
        }
        $sdkVersion = $request->headers->get('x-esp8266-sdk-version');
        $version = $request->headers->get('x-esp8266-version');
        $userAgent = $request->headers->get('user-agent');
        $mac = $request->headers->get('x-esp8266-sta-mac');
        
        // Query OTA Binary
        $otaDeviceMac = $this->container->get('doctrine')->getManager()
                ->createQueryBuilder()
                ->select('dm')
                ->from('UEGMobileArduinoOTAServerBundle:OTADeviceMac', 'dm')
                ->innerJoin('dm.otaBinary', 'ob')
                ->where('dm.mac = :mac')
                ->andWhere('ob.sdkVersion = :sdkVersion')
                ->andWhere('ob.binaryVersion = :binaryVersion')
                ->andWhere('ob.userAgent = :userAgent')
                ->setParameter('mac',$mac)
                ->setParameter('sdkVersion',$sdkVersion)
                ->setParameter('binaryVersion',$version)
                ->setParameter('userAgent',$userAgent)
                ->addOrderBy("ob.createdAt", "DESC")
                ->setMaxResults(1)
                ->getQuery()->getResult();
                ;
        if(!empty($otaDeviceMac)){
            // Return OTA Binary
            $otaBinary = $otaDeviceMac[0]->getOtaBinary();
            $response = new Response(stream_get_contents($otaBinary->getBinaryFile()), 200, array(
                'Content-Type' => 'application/octet-stream',
                'Content-Length' => sizeof($otaBinary->getBinaryFile()),
                'Content-Disposition' => 'attachment; filename="'.$otaBinary->getBinaryName().'"',
            ));        
            return $response;
        }
        
        $view->setStatusCode(500);
        $view->setData('No version for ESP MAC');
        return $view;
    }

}
