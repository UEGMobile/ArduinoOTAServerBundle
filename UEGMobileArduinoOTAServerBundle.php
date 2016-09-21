<?php

namespace UEGMobile\ArduinoOTAServerBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use UEGMobile\ArduinoOTAServerBundle\CompilerPass\MappingCompilerPass;

class UEGMobileArduinoOTAServerBundle extends Bundle
{      
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new MappingCompilerPass());
    }

}
