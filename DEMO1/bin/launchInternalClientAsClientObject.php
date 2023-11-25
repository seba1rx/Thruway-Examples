<?php

require __DIR__ . '/../../vendor/autoload.php';

use Thruway\ClientSession;
use Thruway\Peer\Client as ThruwayPeerClient;
use Thruway\Transport\PawlTransportProvider;
use DEMO1\SimpleClientAuth;

/**
 * this acts like an internal client 2 using a procedural style
 */

$client = new ThruwayPeerClient("somerealm");
$client->addTransportProvider(new PawlTransportProvider("ws://127.0.0.1:9090/"));
$client->addClientAuthenticator(new SimpleClientAuth());

$client->on('open', function (ClientSession $session) {

    $session->call('com.example.getfreespace', null)->then(
        function ($res) {
            echo "Result: {$res}\n";
        },
        function ($error) {
            echo "Call Error: {$error}\n";
        }
    );

    $session->call('com.example.getMockData', null)->then(
        function ($res) {
            echo "Result: {$res}\n";
        },
        function ($error) {
            echo "Call Error: {$error}\n";
        }
    );

});

$client->start();