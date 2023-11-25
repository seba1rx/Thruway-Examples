<?php

require __DIR__ . '/../../vendor/autoload.php';

use DEMO1\InternalClient2;
use DEMO1\SimpleClientAuth;

$client2 = new InternalClient2('somerealm');

$client2->addClientAuthenticator(new SimpleClientAuth());

$client2->addTransportProvider(new \Thruway\Transport\PawlTransportProvider('ws://127.0.0.1:9090/'));

$client2->start();
