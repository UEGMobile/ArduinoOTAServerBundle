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

This plugin is developer for [Arduino core for ESP8266 WiFi chip](https://github.com/esp8266/Arduino/). See section https://github.com/esp8266/Arduino/tree/master/doc/ota_updates#http-server

Include in version 2:

- New 'Program' Entity

- New 'ArduinoOTAServerService' Service

- New concole commands

Since v.1.2.0 compatible with  [Arduino core for ESP32 WiFi chip] (https://github.com/espressif/arduino-esp32).


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

    $ php app/console doctrine:migrations:diff (Symonfy 2.*)
    $ php bin/console doctrine:migrations:diff (Symonfy 3.*)

    $ php app/console doctrine:migrations:migrate (Symonfy 2.*)
    $ php bin/console doctrine:migrations:migrate (Symonfy 3.*)

IMPORTANT NOTE: Upgrades to v2.0.0 needs some tasks:

    - create and execute doctrine migrations.
    - register one program (see register program console command)
    - update devices and set program and mode (see update device console command)

NOTE: Upgrades to v1.2.0 from previous versions require run doctrine commands again.

Usage
-----

Register programs with Symfony console commands
---------------------------------------

* Register new program to be available in OTA server

    $ php bin/console aotaserver:register:program porgramName 

Example:

    $ php bin/console aotaserver:register:program "Program A"
    Register Program "Program A" with id X done!

* List all programs availables in OTA server

    $ php bin/console aotaserver:list:program

Example:

    $ php bin/console aotaserver:list:program
    +----+-----------------+------------------+------------------+------------------+
    | Id | Binary Name     | Alpha            | Beta             | Prod             |
    +----+-----------------+------------------+------------------+------------------+
    | 1  | Program A       | (none)           | (none)           | (none)           |
    +----+-----------------+------------------+------------------+------------------+

Register binaries with Symfony console commands
---------------------------------------

* Register new binary to be available in OTA server

    $ php bin/console aotaserver:register:binary binaryName binaryVersion userAgent sdkVersion binaryPath

Example:

    $ php bin/console aotaserver:register:binary binary.1.bin 1.0.0 ESP8266-http-Update '1.5.3(aec24ac9)' arduino.1.2.02.bin
    Register arduino.1.2.02.bin done!

* List all binaries availables in OTA server

    $ php bin/console aotaserver:list:binary

Example:

    $ php bin/console aotaserver:list:binary
    +----+--------------+---------------------+-----------------+
    | Id | Binary Name  | User-Agent          | SDK Version     |
    +----+--------------+---------------------+-----------------+
    | 1  | binary.1.bin | ESP8266-http-Update | 1.5.3(aec24ac9) |
    +----+--------------+---------------------+-----------------+


Register device with Symfony console commands
---------------------------------------

* Register new device to be available in OTA server

    $ php bin/console aotaserver:register:device mac programId mode

Example:

    $ php bin/console aotaserver:register:device '5C:CF:7F:8C:54:12' 1 BETA
    Register 5C:CF:7F:8C:54:12 with id X done!

* List all devices availables in OTA server

    $ php bin/console aotaserver:list:device

Example:

    $ php app/console aotaserver:list:device
    +----+-------------------+--------+---------------+-------+
    | Id | MAC Address       | Active | Program       | Mode  |
    +----+-------------------+--------+---------------+-------+
    | 11 | 5C:CF:7F:8C:54:12 | N      | 1 - Program A | BETA  |
    +----+-------------------+--------+---------------+-------+

Update device with Symfony console commands
---------------------------------------

* Update configuration device and set Beta, Alpha or Prod Mode in OTA server

    $ php bin/console aotaserver:update:device deviceId programId mode active

Example:

    $ php bin/console aotaserver:update:device 1 1 BETA
    Updated 12:23:44:55:66 with id 1 done!


Update device with Symfony console commands
---------------------------------------

* Update configuration device and set Beta, Alpha or Prod Mode in OTA server

    $ php bin/console aotaserver:update:device deviceId programId mode active

Example:

    $ php bin/console aotaserver:update:device 1 1 BETA
    Updated 12:23:44:55:66 with id 1 done!



Update program with Symfony console commands
---------------------------------------

* Update name progam in OTA server

    $ php bin/console aotaserver:update:program programId name

Example:

    $ php bin/console aotaserver:update:program 1 "New program name"
    Updated New program name with id 1 done!

Control entities from a Service
---------------------------------------

The ArduinoOTAServerService is available from the container:

    $arduinoOTAServerService = $this->container->get('arduino_ota_server_service');

ArduinoOTAServerService public methods to get entities are:

- public function getProgram(string $programId): ?OTAProgram 
- public function getDevice(string $deviceId): ?OTADeviceMac  
- public function getBinary(string $binaryId): ?OTABinary

ArduinoOTAServerService public methods to search entities are:

- public function searchPrograms(string $programName = null, int $page = 1, int $limit = 10, array $sort = []): ?Pagerfanta
- public function searchDevices(string $deviceMAC = null, int $page = 1, int $limit = 10, array $sort = []): ?Pagerfanta 
- public function searchBinaries(string $binaryName = null, string $binaryVersion = null, string $userAgent = null, string $sdkVersion = null, int $page = 1, int $limit = 10,array $sort = []): ?Pagerfanta

ArduinoOTAServerService public methods to register entities are:

- public function registerProgram(string $programName): OTAProgram
- public function registerDevice(string $deviceMAC, OTAProgram $program, string $mode = OTADeviceMac::MODE_ALPHA): OTADeviceMAC
- public function registerBinary(string $binaryName, string $binaryVersion, string $userAgent, string $sdkVersion, $binaryFile): OTABinary 

ArduinoOTAServerService public methods to update entities are:

- public function updateProgram($programId, $programName = null, $binaryAlpha = null, $binaryBeta = null, $binaryProd = null): OTAProgram
- public function updateDevice($deviceId, OTAProgram $program = null, string $mode = OTADeviceMac::MODE_ALPHA, bool $active = null): OTADeviceMAC

ArduinoOTAServerService public method to get OTA binary is:

- public function searchBinaryByMACAddress(string $MACAddress): ?OTABinary

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
