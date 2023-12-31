# Exploring voryx/Thruway's basics

##### Thruway is an open source client and router implementation of [WAMP (Web Application Messaging Protocol)](http://wamp.ws/), for PHP.  Thruway uses an event-driven, non-blocking I/O model ([reactphp](http://reactphp.org/)), perfect for modern real-time applications.

### WAMP = RPC + PubSub

Thruway lets you implement a Router, which is not a server, but when you add RPC and/or PubSub you can say the router acts like a server, so I'll be refering the router as **router** when talking about the router class, and I'll be refering the router as **server** when talking about the instance and how it was configured.

#### This is a voryx/Thruway demo implementation I built in order to understand its basics

Please check the original repo [examples](https://github.com/voryx/Thruway/tree/master/Exampleshttps:/) in order to compare this implementation to the original examples.

## DEMO 1

### **Demo 1: exploring RPC and internal clients**

This demo has:

* Router
* Realm Authentication: based on Examples/Authentication/SimpleAuthProviderClient.php
* Internal Client 1: based on Examples/ClientClass/FreeSpaceClient.php
* Autobahn client: based on the autobahn-js [repo](https://github.com/crossbario/autobahn-js) and Examples/Authentication/simpleAutobahnAuth.html
* Internal Client 2 (connection object): based on Examples/SimpleClient.php

### How to use demo 1

Open 3 terminals and a browser tab with console in "console" option (F12 in Chrome, select console tab)

navigate to root dir, at the same level of composer.json,
so if you do ls/dir command, you should see composer.json

**in terminal 1:**

> php DEMO1/bin/router.php

This will launch the router, add auth manager and auth client, and add transport provider.

**in terminal 2:**

> php DEMO1/bin/launchInternalClient.php

This will start the internal client using an instance of the InternalClient.php, using a client authenticator to enter the realm, this client will register some methods in order to make RPCs.

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

---

### DEMO 2

#### Router, internal client 1 and auth provider client in the same router instance

**open 1 terminal and run the router like this:**

> php DEMO2/bin/router.php

This should start the router creating and adding to the router a transport provider, create and add an auth provider client for the realm, register an authentication manager module in one single file. This is a much more clean implementation.

**open a browser, open console in "console tab": lets call the connection object using jquery ajax**

* go to the url usen in your demo, probably 127.0.0.1 if you haven't a vhost for local dev
* wait until you see the RPC responses
* click the button and see the logs

The idea in this example (demo 2) is:

-The client 1 (the one attached to the router) will be registering RPC methods

-The browser will make an XHR (using jquery ajax) to run the connection object (client 2), the connection object will make the RPC call to the methods registered by the client 1.

**I added a convenient button in the index.php so you can click it to make the XHR.**

---

### DEMO 3

#### metaevents

**This is an implementation of Metaevents in the context of a login controller, this is a demo, not suited for production**

**Lets try to prevent multiple users to use same credentials to log in**

##### I'm basing this demo in DEMO1 and DEMO2, so please go and check those if you get confused

The usual way a simple system checks for credentias is the following:

* the browser client sends user + password to login controller
* the login controller looks in the DB for a match in the users table and get the user_id
* if success, gets some data in DB and builds $_SESSION data and do session_start()
* client browser gets ok and redirects to wellcome page

This demo will do the following:

* the browser client sends user + password to login controller
* the login controller looks in the DB for a match in the users table and get the user_id
* **if success, the login controller will use the connection object to try to connect to WAMP server and find out if there is another connection to the WAMP server using the user id**
* if success, gets some data in DB and builds $_SESSION data and do session_start()
* client browser gets ok and redirects to wellcome page
* **when requesting welcome page, the browser client will connect to WAMP server, and use the user_id to do an RPC to tell the WAMP server that the current connection is using the user_id**

### Run this demo

**open 1 terminal and run the router like this:**

> php DEMO3/bin/router.php

**open a browser tab, open the console in "console" tab, use the button in index.php to go to DEMO3 (metaevents.php)**

log in so you get to wellcome

**open a browser tab in incognito mode, or a different browser, open the console in "console" tab, use the button in index.php to go to DEMO3 (metaevents.php)**

log in using the same credentials as the first tab, so you get an error, "a user is already connected using the provided credentials"

---

I'll be adding more functionality to this example.

If you find any bug, or you want to contribute adding more functionality just let me know
