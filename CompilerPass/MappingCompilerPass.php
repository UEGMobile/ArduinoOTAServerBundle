<?php

namespace UEGMobile\ArduinoOTAServerBundle\CompilerPass;

use Symfony\Component\DependencyInjection\ContainerBuilder;

use Mmoreram\SimpleDoctrineMapping\CompilerPass\Abstracts\AbstractMappingCompilerPass;

/**
 * Class MappingCompilerPass.
 */
class MappingCompilerPass extends AbstractMappingCompilerPass
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        $this
            ->addEntityMapping(
                $container,
                'default',
                'UEGMobile\ArduinoOTAServerBundle\Entity\OTABinary',
                '@UEGMobileArduinoOTAServerBundle/Resources/config/doctrine/OTABinary.orm.yml',
                true
            )
            ->addEntityMapping(
                $container,
                'default',
                'UEGMobile\ArduinoOTAServerBundle\Entity\OTADeviceMac',
                '@UEGMobileArduinoOTAServerBundle/Resources/config/doctrine/OTADeviceMac.orm.yml',
                true
            )                
        ;
    }
}
