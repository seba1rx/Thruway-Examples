<?php


require __DIR__ . "/../../vendor/autoload.php";

use Thruway\Peer\Router;
use Thruway\Transport\RatchetTransportProvider;

$theRealm = [
    "name" => "somerealm",
    "signature" => "letMeIn"
];

$router = new Router();
$loop = $router->getLoop();

// lets use the default transport
$transportProvider = new RatchetTransportProvider("127.0.0.1", 9090);

$router->addTransportProvider($transportProvider);

// lets use the demo3 auth provider client in order not to bloat the demo with duplicate files
$realmAuthProvider = new \DEMO3\AuthProviderClient([$theRealm["name"]], $loop);
$realmAuthProvider->setSignature($theRealm["signature"]);

/** adds auth provider client in router */
$router->addInternalClient($realmAuthProvider);

/** registers the authentication module so it requires the auth provider client */
$router->registerModule(new \Thruway\Authentication\AuthenticationManager());

// we will need the loop object to create the periodic publishing to the topic "demo6"
$router->addInternalClient(new \DEMO6\InternalClient6($theRealm["name"], $loop));

$router->start();