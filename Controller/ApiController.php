<?php

namespace UEGMobile\ArduinoOTAServerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     */
    public function getUpdateBinaryAction(Request $request)
    {
        $view = View::create();

        $esp8266 = false;
        $esp32 = false;
        $sdkVersion = null;
        $version = null;
        $userAgent = null;
        $mac = null;
        if($request->headers->get('x-esp8266-sta-mac') &&
            $request->headers->get('x-esp8266-ap-mac') &&
            $request->headers->get('x-esp8266-free-space') &&
            $request->headers->get('x-esp8266-sketch-size') &&
            $request->headers->get('x-esp8266-chip-size') &&
            $request->headers->get('x-esp8266-sdk-version') &&
            $request->headers->get('x-esp8266-version')
        ){
            $esp8266 = true;
            $this->container->get('logger')->debug('ESP8266 detected!');
            $sdkVersion = $request->headers->get('x-esp8266-sdk-version');
            $version = $request->headers->get('x-esp8266-version');
            $userAgent = $request->headers->get('user-agent');
            $mac = $request->headers->get('x-esp8266-sta-mac');
        }

        if($request->headers->get('x-esp32-sta-mac') &&
            $request->headers->get('x-esp32-ap-mac') &&
            $request->headers->get('x-esp32-sdk-version')
        ){
            $esp32 = true;
            $this->container->get('logger')->debug('ESP32 detected!');
            $sdkVersion = $request->headers->get('x-esp32-sdk-version');
            $version = $request->headers->get('x-esp32-version');
            $userAgent = $request->headers->get('user-agent');
            $mac = $request->headers->get('x-esp32-sta-mac');
        }

        if (!$esp32 && !$esp8266){
            return new Response('Only for ESP8266 and ESP32 updater!', 460);
        }

        // Query OTA Binary
        $otaDeviceMac = $this->container->get('doctrine')->getManager()
            ->createQueryBuilder()
            ->select('dm')
            ->from('UEGMobileArduinoOTAServerBundle:OTADeviceMac', 'dm')
            ->innerJoin('dm.otaBinary', 'ob')
            ->where('dm.mac = :mac')
            ->andWhere('dm.active = 1')
            ->andWhere('ob.sdkVersion = :sdkVersion')
            ->andWhere('ob.userAgent = :userAgent')
            ->setParameter('mac',$mac)
            ->setParameter('sdkVersion',$sdkVersion)
            ->setParameter('userAgent',$userAgent)
            ->addOrderBy("ob.createdAt", "DESC")
            ->setMaxResults(1)
            ->getQuery()->getResult();
        ;
        if(count($otaDeviceMac) > 0 && $version !== $otaDeviceMac[0]->getOtaBinary()->getBinaryVersion()){
            // Return OTA Binary
            $otaBinary = $otaDeviceMac[0]->getOtaBinary();
            $file = $otaBinary->getBinaryFile();
            $response = new Response(stream_get_contents($file), 200, array(
                'Content-Type' => 'application/octet-stream',
                'Content-Length' => fstat($file)['size'],
                'Content-Disposition' => 'attachment; filename="'.$otaBinary->getBinaryName().'"',
            ));
            $this->container->get('logger')
                ->debug('Download '.$otaBinary->getBinaryName().' (id:'.$otaBinary->getId().')');

            $otaDeviceMac->setActive(false);
            $em = $this->container->get('doctrine')->getManager();
            $em->persist($otaDeviceMac);
            $em->flush();

            return $response;
        }
        $this->container->get('logger')->debug('No updated version available for MAC');
        return new Response('No updated version available', 461);
    }

}
