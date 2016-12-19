<?php

require_once(__DIR__ . '../config/config.php');
require_once(__DIR__ . '../helpers/system_helper.php');

function __autoload($className)
{
    require_once('libraries/' . $className . '.php');
}
