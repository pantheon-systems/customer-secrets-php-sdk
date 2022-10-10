<?php

// If this file moves, please change this location to point to
// the composer.json file's directory
define("BOOTSTRAP_ROOT_DIR", realpath(__DIR__ . '/..'));
define("BOOTSTRAP_CODE_PATH", BOOTSTRAP_ROOT_DIR. '/src');

require_once BOOTSTRAP_ROOT_DIR . "/vendor/autoload.php";

function bootstrapInclude($file)
{
    require BOOTSTRAP_ROOT_DIR . '/src/' . $file;
}

function bootstrapDotenv()
{
    bootstrapInclude('Dotenv.php');
}

