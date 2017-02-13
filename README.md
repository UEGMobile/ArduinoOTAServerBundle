ArduinoOTAServerBundle
==================

[![Latest Stable Version](https://poser.pugx.org/uegmobile/arduino-ota-server-bundle/v/stable)](https://packagist.org/packages/uegmobile/arduino-ota-server-bundle)
[![Total Downloads](https://poser.pugx.org/uegmobile/arduino-ota-server-bundle/downloads)](https://packagist.org/packages/uegmobile/arduino-ota-server-bundle)
[![Latest Unstable Version](https://poser.pugx.org/uegmobile/arduino-ota-server-bundle/v/unstable)](https://packagist.org/packages/uegmobile/arduino-ota-server-bundle)
[![License](https://poser.pugx.org/uegmobile/arduino-ota-server-bundle/license)](https://packagist.org/packages/uegmobile/arduino-ota-server-bundle)

The **ArduinoOTAServerBundle** provides a HTTP Server to manage OTA 
for Arduino with ESP8266 wifi chip.

Documentation
-------------

This plugin is developer for [Arduino core for ESP8266 WiFi chip](https://github.com/esp8266/Arduino/). See section https://github.com/esp8266/Arduino/blob/master/doc/ota_updates/readme.md#http-server

For documentation, see:

    Resources/doc/

Installation
------------

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

    $ composer require uegmobile/arduino-ota-server-bundle

This command requires you to have Composer installed globally, as explained
in the `installation chapter`_ of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

Then, enable the bundle by adding it to the list of registered bundles
in the ``app/AppKernel.php`` file of your project:

    <?php
    // app/AppKernel.php

    // ...
    class AppKernel extends Kernel
    {
        public function registerBundles()
        {
            $bundles = array(
                // ...
                new UEGMobile\ArduinoOTAServerBundle\UEGMobileArduinoOTAServerBundle(),
            );

            // ...
        }

        // ...
    }

Step 3: Register the Routes
---------------------------

Import the routing definition in ``routing.yml``:

    # app/config/routing.yml
    UEGMobileArduinoOTAServerBundle:
      resource: "@UEGMobileArduinoOTAServerBundle/Resources/config/routing.yml"
      prefix:   /aotaserver

Step 4: Update Database Model
---------------------------

Run doctrine commands to update your database model:

    $ php app/console doctrine:migrations:diff

    $ php app/console doctrine:migrations:migrate

Usage
-----

Register binaries with Symfony commands
---------------------------------------

* Register new binary to be available in OTA server

    $ php app/console aotaserver:register:binary <binaryName> <binaryVersion> <userAgent> <sdkVersion> <binaryPath>

Example:

    $ php app/console aotaserver:register:binary arduino.1.2.02.bin 1.2.02 ESP8266-http-Update '1.5.3(aec24ac9)' arduino.1.2.02.bin
    Register arduino.1.2.02.bin done!

* Register a MAC id with a OTA Binary

    $ php app/console aotaserver:register:mac <mac> <binaryId>

Example:

    $ php app/console app/console aotaserver:register:mac '5C:CF:7F:8C:54:12' 2
    Register 5C:CF:7F:8C:54:12 for 2 done!

* List all binary files availables in OTA server

    $ php app/console aotaserver:list

Example:

    $ php app/console aotaserver:list
    +----+----------------------------+---------------------+-----------------+
    | Id | Binary Name                | User-Agent          | SDK Version     |
    +----+----------------------------+---------------------+-----------------+
    | 2  | arduino.1.2.02.bin         | ESP8266-http-Update | 1.5.3(aec24ac9) |
    +----+----------------------------+---------------------+-----------------+

Control binaries with Symfony service
---------------------------------------

TODO: next version

Configure arduino
------------------------

See section https://github.com/esp8266/Arduino/blob/master/doc/ota_updates/readme.md#http-server

    void upgrade_firmware(){
        Serial.println("upgrade_firmware...");

        t_httpUpdate_return ret = ESPhttpUpdate.update(CLOUD_SERVER_IP, 
            CLOUD_SERVER_PORT, 
            "/app_dev.php/aotaserver/updateBinary",
            FIRMWARE_VERSION);
            
        switch(ret) {
            case HTTP_UPDATE_FAILED:
                Serial.println("[update] Update failed.");
                break;
            case HTTP_UPDATE_NO_UPDATES:
                Serial.println("[update] Update no Update.");
                break;
            case HTTP_UPDATE_OK:
                Serial.println("[update] Update ok."); // may not called we reboot the ESP
                break;
        }
        Serial.println("upgrade_firmware...done!  ");
    }

License
-------

This bundle is released under the MIT license.