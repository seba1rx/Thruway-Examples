<?php

require __DIR__ . '/../vendor/autoload.php';

use Thruway\ClientSession;
use Thruway\Connection;
use Psr\Log\NullLogger;     // used to prevent logger to output stuff
use Thruway\Logging\Logger; // used to prevent logger to output stuff

Logger::set(new NullLogger()); // shush the logger so it doesn't pollute the output of this example

/**
 * this is a procedural client you can place between any code in your main app
 *
 * this acts like internal client 2
 *
 * this can be used to make RPC calls
 *
 * the InternalClient.php will be registering RPC functions you can call from index.php or from here
 *
 * this procedural code is intended to be a mirror of an autobahn.js implementation, in fact, just take a look at js/script.js
 *
 */


$onClose = function ($msg) {
    // echo $msg;

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


/**
 * please note that if you want to keep the client connected
 * you have to delete $connection->close()
 *
 * this is not the case, since we want to execute this code to make the RPC call and obtain the response.
 * An example scenario would be to make an XHR request from the browser using something like jquery ajax, then
 * a file containing this code will act as a client connecting to the router and make the RPC call, obtaining
 * whaterver data you need, then disconnect and return the obtained data. If you delete $connection->close()
 * you won't get a response as the process of the client keeps going on forever
 *
 * TL;DR: client connects, makes RPC call, gets data, disconnects
 */

try{


    $data = [];

    /**
     * we want to close connection after the RPC calls in order to return $data, but
     * since this demo is making 2 RPC, we close connection in the second call
     */

    $connection->on(
        'open',
        function (ClientSession $session) use ($connection, &$data){

            $session->call('com.example.getMockData', null)->then(
                function ($res) use ($connection, &$data){
                    // echo "Result: {$res}\n";

                    $data['getMockData'] = $res[0];

                    // $connection->close();
                },
                function ($error) use ($connection, &$data){
                    // echo "Call Error: {$error}\n";

                    $data['error'] = $error;

                    $connection->close();
                }
            );

            $session->call('com.example.getFreeSpace', null)->then(
                function ($res) use ($connection, &$data){
                    // echo "Result: {$res}\n";

                    $data['getFreeSpace'] = $res[0];

                    $connection->close();
                },
                function ($error) use ($connection, &$data){
                    // echo "Call Error: {$error}\n";

                    $data['error'] = $error;

                    $connection->close();
                }
            );
        }
    );

    $connection->open();
    echo json_encode($data);

}catch(\Exception $e){

    echo json_encode(["error" => "server is down or too much time to execute, sorry"]);
}