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


Usage
-----

TODO...
