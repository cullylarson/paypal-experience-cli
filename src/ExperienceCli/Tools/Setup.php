<?php

namespace PayPal\ExperienceCli\Tools;

use PayPal\ExperienceCli\Container\Config;

class Setup {
    /**
     * Does the following:
     * - Includes the composer autoloader
     */
    public static function DoSetup() {
        /*
         * Include the autoloader
         */

        $autoloadPaths = array(
            __DIR__ . "/../../../../../autoload.php", // likely location, if installed as a vendor package
            __DIR__ . "/../../../vendor/autoload.php", // dev location
        );


        foreach($autoloadPaths as $path) {
            if(file_exists($path)) {
                require($path);
                break;
            }
        }

        // if we didn't find the autoloader, your autoloader is in a stupid place, and you're on your own
    }

    /**
     * Loads environment variables from the .env file in the current working directory (getcwd), and puts them in a
     * Config container.
     *
     * @return Config
     */
    public static function GetConfig() {
        /*
         * Load environment variables (only if we have a .env file)
         */

        $envFilename = ".env";
        $envDir = getcwd();
        $envPath = "{$envDir}/{$envFilename}";

        if( file_exists($envPath) ) {
            \Dotenv::load($envDir, $envFilename);
        }

        /*
         * Make sure required environment variables are set
         */

        \Dotenv::required(array("PAYPAL_CLIENT_ID", "PAYPAL_CLIENT_SECRET", "PAYPAL_ENDPOINT_MODE", "PAYPAL_EXPERIENCE_CLI_PROFILES_DIR"));

        /*
         * Create a config object and return it
         */

        $config = new Config(getenv("PAYPAL_CLIENT_ID"), getenv("PAYPAL_CLIENT_SECRET"), getenv("PAYPAL_ENDPOINT_MODE"), getenv("PAYPAL_EXPERIENCE_CLI_PROFILES_DIR"), getenv("PAYPAL_ENABLE_LOG"), getenv("PAYPAL_LOG_FILENAME"));

        // make sure the profiles directory is valid
        if(!$config->GetProfilesDirAbsolute()) {
            die("ERROR / Could not find profiles directory.\n");
        }

        return $config;
    }
}
