<?php

require __DIR__ . "/../vendor/autoload.php";

use Thruway\Peer\Router;
use Thruway\Transport\RatchetTransportProvider;

$router = new Router();
$loop = $router->getLoop();

$theRealm = [
    "name" => "somerealm",
    "signature" => "letMeIn"
];

$router->registerModule(new RatchetTransportProvider("127.0.0.1", 9090));

/**
 * Creates an authorization provider so the server is not open to anyone trying to connect
 * Set a password to the main realm, so new connections can authenticate
 */
$realmAuthProvider = new \Demo\AuthProviderClient([$theRealm["name"]], $loop);
$realmAuthProvider->setSignature($theRealm["signature"]);

/**
 * adds auth provider client in router
 */
$router->addInternalClient($realmAuthProvider);
/**
 * registers the authentication module so it requires the auth provider client
 */
$router->registerModule(new \Thruway\Authentication\AuthenticationManager());


// $router->addInternalClient(new \Demo\InternalClient3());
$router->addInternalClient(new \Demo\InternalClient3($theRealm["name"], $loop));

$router->start();