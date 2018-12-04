<?php

namespace UEGMobile\ArduinoOTAServerBundle\Service;

use UEGMobile\ArduinoOTAServerBundle\Repository\OTABinaryRepository;
use UEGMobile\ArduinoOTAServerBundle\Repository\OTADeviceMacRepository;
use UEGMobile\ArduinoOTAServerBundle\Repository\OTAProgramRepository;
use Monolog\Logger;
use Doctrine\Common\Collections\Collection;
use UEGMobile\ArduinoOTAServerBundle\Entity\OTAProgram;
use UEGMobile\ArduinoOTAServerBundle\Entity\OTADeviceMac;
use UEGMobile\ArduinoOTAServerBundle\Entity\OTABinary;
use Pagerfanta\Pagerfanta;

class ArduinoOTAServerService
{
    private $logger;
    private $otaBinaryRepository;
    private $otaDeviceMacRepository;
    private $otaProgramRepository;

    public function __construct(
        OTABinaryRepository $otaBinaryRepository,
        OTADeviceMacRepository $otaDeviceMacRepository,
        OTAProgramRepository $otaProgramRepository,
        Logger $logger
    )
    {
        $this->otaBinaryRepository = $otaBinaryRepository;
        $this->otaDeviceMacRepository = $otaDeviceMacRepository;
        $this->otaProgramRepository = $otaProgramRepository;
        $this->logger = $logger;
    }

    public function getProgram(string $programId): ?OTAProgram {
        return $this->otaProgramRepository->findOneById($programId);
    }

    public function getDevice(string $deviceId): ?OTADeviceMac {
        return $this->otaDeviceMacRepository->findOneById($deviceId);
    }

    public function getBinary(string $binaryId): ?OTABinary {
        return $this->otaBinaryRepository->findOneById($binaryId);
    }

    public function searchPrograms(
        string $programName = null,
        int $page = 1,
        int $limit = 10,
        array $sort = []
    ): ?Pagerfanta {
        
        return $this->otaProgramRepository->findAllPaginated(
            $sort,
            $limit,
            $page,
            $programName
        );
    }
    

    public function searchDevices(
        string $deviceMAC = null,
        int $page = 1,
        int $limit = 10,
        array $sort = []
    ): ?Pagerfanta {
        
        return $this->otaDeviceMacRepository->findAllPaginated(
            $sort,
            $limit,
            $page,
            $deviceMAC
        );
    }

    public function searchBinaries(
        string $binaryName = null,
        string $binaryVersion = null,
        string $userAgent = null,
        string $sdkVersion = null,
        int $page = 1,
        int $limit = 10,
        array $sort = []
    ): ?Pagerfanta {
        
        return $this->otaBinaryRepository->findAllPaginated(
            $sort,
            $limit,
            $page,
            $binaryName,
            $binaryVersion,
            $userAgent,
            $sdkVersion
        );
    }

    public function searchBinaryByMACAddress(
        string $MACAddress
    ): ?OTABinary {
        
        $binary = null;
        $device = $this->otaDeviceMacRepository->findByMACAddress($MACAddress);

        $program = $device->getProgram();
        if(!is_null($program)){
            if ($device->getMode() == OTADeviceMac::MODE_ALPHA){
                $binary = $program->getBinaryAlpha();
            }else if ($device->getMode() == OTADeviceMac::MODE_BETA){
                $binary = $program->getBinaryBeta();
            }else if ($device->getMode() == OTADeviceMac::MODE_PROD){
                $binary = $program->getBinaryProd();
            }
        }

        return $binary;
    }

    public function registerProgram(
        string $programName
    ): OTAProgram{
        $program = new OTAProgram();
        $program->setProgramName($programName);
        $this->otaProgramRepository->add($program);
        return $program;
    }


    public function registerDevice(
        string $deviceMAC,
        OTAProgram $program,
        string $mode = OTADeviceMac::MODE_ALPHA
    ): OTADeviceMAC {

        $device = new OTADeviceMac();
        $device->setMac($deviceMAC);
        $device->setProgram($program);
        $device->setMode($mode);
        $this->otaDeviceMacRepository->add($device);

        return $device;
    }

    public function registerBinary(
        string $binaryName,
        string $binaryVersion,
        string $userAgent,
        string $sdkVersion,
        $binaryFile
    ): OTABinary {
        $binary = new OTABinary();
        $binary->setBinaryName($binaryName);
        $binary->setBinaryVersion($binaryVersion);
        $binary->setUserAgent($userAgent);
        $binary->setSdkVersion($sdkVersion);
        $binary->setBinaryFile($binaryFile);
        $this->otaBinaryRepository->add($binary);
        return $binary;
    }

    public function updateProgram(
        $programId,
        $programName = null,
        $binaryAlpha = null,
        $binaryBeta = null,
        $binaryProd = null
    ): OTADeviceMAC{

        $program = $this->otaProgramRepository->findOneById($programId);
        if(!is_null($programName)){
            $program->setProgramName($programName);
        }
        if(!is_null($binaryAlpha)){
            $program->setBinaryAlpha($binaryAlpha);
        }
        if(!is_null($binaryBeta)){
            $program->setBinaryBeta($binaryBeta);
        }
        if(!is_null($binaryProd)){
            $program->setBinaryProd($binaryProd);
        }
        $program->setUpdatedAt(new \DateTime());
        $this->otaProgramRepository->add($program);
        return $program;
    }


    public function updateDevice(
        $deviceId,
        OTAProgram $program = null,
        string $mode = OTADeviceMac::MODE_ALPHA,
        bool $active = null
    ): OTADeviceMAC{

        $device = $this->otaDeviceMacRepository->findOneById($deviceId);
        if(!is_null($program)){
            $device->setProgram($program);
        }
        $device->setMode($mode);
        if(!is_null($active)){
            $program->setActive($active);
        }
        $device->setUpdatedAt(new \DateTime());
        $this->otaDeviceMacRepository->add($device);
        return $device;
    }

}
