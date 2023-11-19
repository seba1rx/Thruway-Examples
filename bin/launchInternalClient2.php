<?php

require __DIR__ . '/../vendor/autoload.php';

use Demo\InternalClient2;
use Demo\SimpleClientAuth;

$client2 = new InternalClient2('somerealm');

$client2->addClientAuthenticator(new SimpleClientAuth());

$client2->addTransportProvider(new \Thruway\Transport\PawlTransportProvider('ws://127.0.0.1:9090/'));

$client2->start();
