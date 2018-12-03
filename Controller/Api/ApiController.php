<?php

namespace UEGMobile\ArduinoOTAServerBundle\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use FOS\RestBundle\View\View,
    FOS\RestBundle\Controller\Annotations\Get,
    FOS\RestBundle\Controller\Annotations\Put,
    FOS\RestBundle\Controller\Annotations\Post,
    FOS\RestBundle\Controller\Annotations\Delete,
    FOS\RestBundle\Controller\Annotations\Route;
use UEGMobile\ArduinoOTAServerBundle\Form\Type\ListProgramsType;
use UEGMobile\ArduinoOTAServerBundle\Form\DTO\ListProgramsDTO;


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

            $otaDeviceMac[0]->setActive(false);
            $em = $this->container->get('doctrine')->getManager();
            $em->persist($otaDeviceMac[0]);
            $em->flush();

            return $response;
        }
        $this->container->get('logger')->debug('No updated version available for MAC');
        return new Response('No updated version available', 461);
    }

    /**
     * @Route("/web-api/public/programs", defaults={"_format" = "json"})
     */
    public function getProgramsAction(Request $request) {

        try {

            $formFactory =$this->container->get('formFactory');
            $form = $formFactory->create(
                ListProgramsType::class,
                new ListProgramsDTO(),
                [ 'csrf_protection' => false ]
            );
            $form->handleRequest($request);
            if (empty($form->getData()) || ($form->isSubmitted() && $form->isValid())) {
                $page = $form->getData()->page;
                $limit = $form->getData()->limit;
                $sort = array_combine(
                    explode(',',$form->getData()->orderParameter()) ?? [],
                    explode(',',$form->getData()->orderValue()) ?? []
                );
                $filterParameters = explode(',',$form->getData()->filterParameter());
                $filterValues = json_decode($form->getData()->filterValue());
                $programNameFilter = null;
                $globalFilter = null;
                $i = 0;
                foreach ($filterParameters as $filterParameter){
                    if($filterParameter == "name"){
                        $programNameFilter = $filterValues[$i];
                    }elseif ($filterParameter == "tablesearch"){
                        $globalFilter = $filterValues[$i];
                    }
                    $i++;
                }
        
                $em = $this->getContainer()->get('doctrine')->getManager();
                $repositoryPrograms = $em->getRepository('UEGMobileArduinoOTAServerBundle:Program');
                $paginatedCollection = $repositoryPrograms->findAllPaginated(
                    $sort,
                    $limit,
                    $page,
                    $programNameFilter,
                    $globalFilter
                );
                $paginatedCollection->setMaxPerPage($limit);
                $paginatedCollection->setCurrentPage($page);
                return (new PagerfantaFactory())->createRepresentation(
                    $paginatedCollection, 
                    new \Hateoas\Configuration\Route('uegmobile_arduinootaserver_api_api_getprograms'));

            } else {
                return new Response(
                    array('message' => 'program.exception.invalid_request'),
                    400);
            }

        } catch (\Exception $e){
            return new Response(
                array('message' =>  $e->getMessage()),
                400);
        }
    }


}
