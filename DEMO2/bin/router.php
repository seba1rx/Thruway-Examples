<?php

require __DIR__ . "/../../vendor/autoload.php";

use DEMO2\InternalClient;
use DEMO2\AuthProviderClient;

$router = new \Thruway\Peer\Router();
$loop = $router->getLoop();

$theRealm = [
    "name" => "somerealm",
    "signature" => "letMeIn"
];

/**
 * Creates a transport provider so we can manage connection events
 */
$transportProvider = new \Thruway\Transport\RatchetTransportProvider("127.0.0.1", 9090);

/**
 * Creates an authorization provider so the server is not open to anyone trying to connect
 * Set a password to the main realm, so new connections can authenticate
 */
$realmAuthProvider = new AuthProviderClient([$theRealm["name"]], $loop);
$realmAuthProvider->setSignature($theRealm["signature"]);

/**
 * Creates an internal client in order to register methods for remoting (RPC calls)
 * In theory this client has the loop object so it can access a lot of things
 */
$internalClient = new InternalClient($theRealm["name"], $loop);

/**
 * registers the authentication module so it requires the auth provider client
 */
$router->registerModule(new \Thruway\Authentication\AuthenticationManager());

/**
 * adds transport provider to router
 */
$router->registerModule($transportProvider);

/**
 * adds auth provider client to router
 */
$router->addInternalClient($realmAuthProvider);

/**
 * adds internal client to router
 */
$router->addInternalClient($internalClient);

/**
 * Starts the wamp server
 */
$router->start();