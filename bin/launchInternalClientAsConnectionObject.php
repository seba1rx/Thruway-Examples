<?php

require __DIR__ . '/../vendor/autoload.php';

use Thruway\ClientSession;
use Thruway\Connection;

/**
 * this is a procedural client you can place between any code in your main app
 *
 * this acts like internal client 2
 *
 * this can be used to make RCP calls
 *
 * the InternalClient.php will be registering RCP functions you can call from index.php or from here
 *
 * this procedural code is intended to be a mirror of an autobahn.js implementation, in fact, just take a look at js/script.js
 *
 */

$onClose = function ($msg) {
    echo $msg;
};

$onChallenge = function($method){

    // if($method == 'simplysimple'){
    //     return "letMeIn";
    // }

    return "letMeIn";
};

$connection = new Connection(
    [
        "realm"   => 'somerealm',
        "onClose" => $onClose,
        "url"     => 'ws://127.0.0.1:9090',
        "onChallenge" => $onChallenge,
        "authmethods" => ['simplysimple'],
    ]
);

$connection->on(
    'open',
    function (ClientSession $session) {

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
    }

);

$connection->open();



