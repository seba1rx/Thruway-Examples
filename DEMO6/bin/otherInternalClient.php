<?php

use Rx\Thruway\Client;
use DEMO6\MockMsg;
use Thruway\Message\ResultMessage;

require __DIR__ . '/../vendor/autoload.php';

/**
 * This client is intended to produce trafic by publishing random content
 * to the "demo6" topic every 5 seconds
 */

 $theRealm = [
    "name" => "somerealm",
    "signature" => "letMeIn"
];

$client = new Client('ws://127.0.0.1:9090', $theRealm['name'], ['authmethods' => ['simplysimple']]);

$client->onChallenge(function() use ($theRealm) {
    return $theRealm['signature'];
});

$source = \Rx\Observable::interval(5000)->do(function () use ($client){
    $client->publish('demo6', json_encode(MockMsg::getRandomMockGreeting()));
});

// subscribe
$client
    ->topic('demo6')
    ->subscribe();

// publish
$client->publish('demo6',  $source->subscribe());