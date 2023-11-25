# DEMO 1:

##### Purpose: have a router, have a realm with authentication, internal client registering RPC methods, call the RPC methos usin internal client 2 and browser client.

**This demo consists of:**

* Router: based on Examples/SimpleWsRouter.php
* Realm Authentication: based on Examples/Authentication/SimpleAuthProviderClient.php
* Internal Client 1: based on Examples/ClientClass/FreeSpaceClient.php
* Autobahn client: based on the autobahn-js [repo](https://github.com/crossbario/autobahn-js) and Examples/Authentication/simpleAutobahnAuth.html
* Internal Client 2 (connection object): based on Examples/SimpleClient.php


### How to use demo 1

Open 3 terminals and a browser tab with console in js option (F12 in Chrome, select js tab)

navigate to root dir, at the same level of composer.json,
so if you do ls/dir command, you should see composer.json

**in terminal 1:**

> php DEMO1/bin/router.php

This will launch the router, add auth manager and auth client, and add transport provider.


**in terminal 2:**

> php DEMO1/bin/launchInternalClient.php

This will start the internal client using an instance of the InternalClient.php, using a client authenticator to enter the realm.


**in terminal 3**, choose one of the following, but try them all at least once:

All of these act like a second internal client. the idea is that internal client 2 can be used by the main app to interface with the WAMP server.


**option 1**, using use Thruway\Connection object:

> php DEMO1/bin/launchInternalClientAsConnectionObject.php


**option 2**, using Thruway\Peer\Client object:

> php DEMO1/bin/launchInternalClientAsClientObject.php


**option 3** using Thruway\Peer\Client subclass:

> php bin/launchInternalCLient2.php

**in browser:** in file index.php in a script tag there is a function defined, in the console in JS tab, call the function
> runConnectionObjectUsingAjax();
