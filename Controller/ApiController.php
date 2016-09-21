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
     * @Get("/updateBinary", name="")
     * @return View
     */
    public function getUpdateBinaryAction()
    {
        
        $view = View::create();
        $request = $this->container->get('request');
        
        $this->container->get('logger')->debug(json_encode($request->headers->all()));
        
        $binarys = $this->container->get('doctrine')->getManager()
                ->getRepository("UEGMobile\ArduinoOTAServerBundle\Entity\OTABinary")
                ->findAll();        
        $binary = $binarys[0];        
        $this->container->get('logger')->debug($binary->getBinaryName());
        $this->container->get('logger')->debug(sizeof($binary->getBinaryFile()));
        $this->container->get('logger')->debug($binary->getBinaryFile());
                
        /*
        if(!$request->headers->get('HTTP_X_ESP8266_STA_MAC') ||
           !$request->headers->get('HTTP_X_ESP8266_AP_MAC') ||
           !$request->headers->get('HTTP_X_ESP8266_FREE_SPACE') ||
           !$request->headers->get('HTTP_X_ESP8266_SKETCH_SIZE') ||
           !$request->headers->get('HTTP_X_ESP8266_SKETCH_MD5') ||
           !$request->headers->get('HTTP_X_ESP8266_CHIP_SIZE') ||
           !$request->headers->get('HTTP_X_ESP8266_SDK_VERSION') ||
           !$request->headers->get('HTTP_X_ESP8266_VERSION')
        ){
            $view->setStatusCode(403);
            $view->setData('Only for ESP8266 updater!');
            return $view;
        }
        */
        $sdkVersion = $request->headers->get('HTTP_X_ESP8266_SDK_VERSION');
        $version = $request->headers->get('HTTP_X_ESP8266_VERSION');
        $userAgent = $request->headers->get('user-agent');
        $mac = $request->headers->get('HTTP_X_ESP8266_STA_MAC');
        
        $mac = 'aaas';
        $sdkVersion = '1.0';
        $version= '1.2';
        
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
                'Content-Length' => sizeof($binary->getBinaryFile()),
                'Content-Disposition' => 'attachment; filename="'.$binary->getBinaryName().'"',
            ));        
            return $response;
        }
        
        $view->setStatusCode(500);
        $view->setData('No version for ESP MAC');
        return $view;
    }

}
