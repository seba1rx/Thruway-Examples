# Exploring voryx/Thruway's basics

##### Thruway is an open source client and router implementation of [WAMP (Web Application Messaging Protocol)](http://wamp.ws/), for PHP.  Thruway uses an event-driven, non-blocking I/O model ([reactphp](http://reactphp.org/)), perfect for modern real-time applications.

#### This is a voryx/Thruway demo implementation I built in order to understand its basics

Please check the original repo [examples](https://github.com/voryx/Thruway/tree/master/Exampleshttps:/) in order to compare this implementation to the original examples.

**This demo consists of:**

* Router: based on Examples/SimpleWsRouter.php
* Realm Authentication: based on Examples/Authentication/SimpleAuthProviderClient.php
* Internal Client 1: based on Examples/ClientClass/FreeSpaceClient.php
* Autobahn client: based on the autobahn-js [repo](https://github.com/crossbario/autobahn-js) and Examples/Authentication/simpleAutobahnAuth.html
* Internal Client 2 (connection object): based on Examples/SimpleClient.php

**The purpose of this implementation is:**

* to have a router
* realm authentication
* internal client 1 registering RPC methods that interface with the main app
* js client based on autobahn.js so browser clients can connect and make RPC calls
* internal client 2 using connection object that will be launched by the main app to interface with the router so it can query the loop object, obtain connected users, etc.

---

Open 3 terminals and a browser tab with console in js option (F12 in Chrome, select js tab)

navigate to root dir, at the same level of composer.json,
so if you do ls/dir command, you should see composer.json


**in terminal 1:**

> php bin/SimpleWsRouter.php

This will launch the router, add auth manager and auth client, and add transport provider.


**in terminal 2:**

> php bin/launchInternalClient.php

This will start the internal client using an instance of the InternalClient.php, using a client authenticator to enter the realm.


**in terminal 3**, choose one of the following, but try them all at least once:

All of these act like a second internal client. the idea is that internal client 2 can be used by the main app to interface with the WAMP server.


**option 1**, using use Thruway\Connection object:

> php bin/launchInternalClientAsConnectionObject.php


**option 2**, using Thruway\Peer\Client object:

> php bin/launchInternalClientAsClientObject.php


**option 3** using Thruway\Peer\Client subclass:

> php bin/launchInternalCLient2.php

**in browser:** in file index.php in a script tag there is a function defined, in the console in JS tab, call the function
> runConnectionObjectUsingAjax();

---

I'll be adding more functionality to this example.

If you find any bug, or you want to contribute adding more functionality just let me know
