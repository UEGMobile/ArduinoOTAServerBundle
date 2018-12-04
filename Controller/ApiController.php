<?php

namespace UEGMobile\ArduinoOTAServerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\Get;

class ApiController extends Controller
{

    /**
     * @Get("/updateBinary")
     */
    public function getUpdateBinaryAction(Request $request)
    {
      
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
            return new Response('Error 460. Only for ESP8266 and ESP32 updater!', 460);
        }

        $arduinoOTAServerService = $this->container->get('arduino_ota_server_service');

        $binary = $arduinoOTAServerService->searchBinaryByMACAddress($mac);
        
        if(is_null($binary)){
            return new Response('Error 461. No updated version available', 461);
        }

        if($binary->getBinaryVersion() == $version ){
            return new Response('Error 462. No updated version available', 462);
        }

        if($binary->getSdkVersion() != $sdkVersion ){
            return new Response('Error 463. No updated version available', 463);
        }

        if($binary->getUserAgent() != $userAgent ){
            return new Response('Error 464. No updated version available', 464);
        }

        $file = $binary->getBinaryFile();
        $response = new Response(stream_get_contents($file), 200, array(
            'Content-Type' => 'application/octet-stream',
            'Content-Length' => fstat($file)['size'],
            'Content-Disposition' => 'attachment; filename="'.$binary->getBinaryName().'"',
        ));
        $this->container->get('logger')->debug('Download '.$binary->getBinaryName().' (id:'.$binary->getId().')');

        return $response;
    }

}
