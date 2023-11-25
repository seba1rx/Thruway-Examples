<?php

require_once __DIR__ . "/../../vendor/autoload.php";

use Thruway\Peer\Router;
use Thruway\Transport\RatchetTransportProvider;
use DEMO1\SimpleAuthProviderClient;


/**
 * this script is based on:
 * https://github.com/voryx/Thruway/blob/master/Examples/Authentication/StartRouter.php
 *
 */

$router = new Router();

$router->registerModule(new \Thruway\Authentication\AuthenticationManager());

//Provide authentication for the realm: 'somerealm'
$authProvClient = new SimpleAuthProviderClient(["somerealm"]);
$router->addInternalClient($authProvClient);

$transportProvider = new RatchetTransportProvider("127.0.0.1", 9090);
$router->addTransportProvider($transportProvider);

$router->start();