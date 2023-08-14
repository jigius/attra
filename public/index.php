<?php

use Local\App;

require_once __DIR__ . "/../vendor/autoload.php";

$cfg = new App\AppCfg();
$uuid = (new App\UserAuthCookieDumb("usr", $cfg->fetch("userAuth.ttl", 0)))->uuid();

echo $uuid;
