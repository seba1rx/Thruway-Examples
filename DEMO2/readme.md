
### DEMO 2

#### Router, internal client 1 and auth provider client in the same router instance

open 1 terminal and run the router like this:

> php DEMO2/bin/router.php

This should start the router creating and adding to the router a transport provider, create and add an auth provider client for the realm, register an authentication manager module in one single file. This is a much more clean implementation.

The idea in this example is:

-The client 1 (the one attached to the router) will be registering RPC methods

-The browser will make an XHR (using jquery ajax) to run the connection object (client 2), the connection object will make the RPC call to the methods registered by the client 1.

**I added a convenient button in the index.php so you can click it to make the XHR.**
