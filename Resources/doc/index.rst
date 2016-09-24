ArduinoOTAServerBundle
==================

The **ArduinoOTAServerBundle** provides a HTTP Server to manage OTA 
arduino binary files.

Installation
------------

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

.. code-block:: bash

    $ composer require uegmobile/arduino-ota-server-bundle

This command requires you to have Composer installed globally, as explained
in the `installation chapter`_ of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

Then, enable the bundle by adding it to the list of registered bundles
in the ``app/AppKernel.php`` file of your project:

.. code-block:: php

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

.. code-block:: yaml

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

Commands
________

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

Service
________

(next version)
