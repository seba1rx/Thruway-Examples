<?php

require __DIR__ . '/../vendor/autoload.php';

use Demo\InternalClient;
use Demo\SimpleClientAuth;

$client = new InternalClient('somerealm');

$client->addClientAuthenticator(new SimpleClientAuth());

$client->addTransportProvider(new \Thruway\Transport\PawlTransportProvider('ws://127.0.0.1:9090/'));

$client->start();
