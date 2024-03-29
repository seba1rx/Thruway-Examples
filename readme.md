# Exploring voryx/Thruway's basics

##### Thruway is an open source client and router implementation of [WAMP (Web Application Messaging Protocol)](http://wamp.ws/), for PHP.  Thruway uses an event-driven, non-blocking I/O model ([reactphp](http://reactphp.org/)), perfect for modern real-time applications.

### WAMP = RPC + PubSub

Thruway lets you implement a Router, which is not a server, but when you add RPC and/or PubSub you can say the router acts like a server, so I'll be refering the router as **router** when talking about the router class, and I'll be refering the router as **server** when talking about the instance and how it was configured.

#### This is a voryx/Thruway demo implementation I built in order to understand its basics

Please check the original repo [examples](https://github.com/voryx/Thruway/tree/master/Exampleshttps:/) in order to compare this implementation to the original examples.

---
## **Demo 1: exploring RPC and internal clients**

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

## DEMO 2: Router, internal client 1 and auth provider client in the same router instance

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

## DEMO 3: Exploring metaevents (on join, on leave)

**This is an implementation of Metaevents in the context of a login controller, this is a demo, not suitable for production**

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

I'll be adding more examples in the future.

If you find any bug, or want to point something out, you are most welcome to do it.

---

## Demo 4 and 5 coming soon

---
## Demo 6: https, reverse proxy and wss

### Connect to the Wamp server using WSS protocol

(Disclaimer: this demo uses apache server but it is recommended to use nginx, this demo will show the apache server only)

For this you will need to configure:

* An apache server enabling mod-proxy
* A self signed certificate (at least)
* A virtual host in the apache server
* The host file (so you can have local dns)
* the autobahn-js connection

There will be an internal client publishing demo messages in the "demo6" topic,
so once you get connected and subscribed to that topic you should start to see those messages.
This is in order to prove everything is working fine.

---
### Lets start with the certificate:

(see https://tecadmin.net/step-by-step-guide-to-creating-self-signed-ssl-certificates/ or another guide online, this is just the first one listed when I googled "self signed certificate")

1- update
> sudo apt update

2- install openssl if not already installed
> sudo apt install openssl

3- go to the dir you will be creating the ssl files
> cd ssl

4- create key
> openssl genrsa -out demo6.key 2048

5- create cretificate signing request (csr)
> openssl req -new -key demo6.key -out demo6.csr

#### you will be asked to enter some info, here is what I did:
```
You are about to be asked to enter information that will be incorporated
into your certificate request.
What you are about to enter is what is called a Distinguished Name or a DN.
There are quite a few fields but you can leave some blank
For some fields there will be a default value,
If you enter '.', the field will be left blank.
-----
Country Name (2 letter code) [AU]:CL
State or Province Name (full name) [Some-State]:Valparaiso
Locality Name (eg, city) []:Vina del mar
Organization Name (eg, company) [Internet Widgits Pty Ltd]:
Organizational Unit Name (eg, section) []:
Common Name (e.g. server FQDN or YOUR name) []:
Email Address []:

Please enter the following 'extra' attributes
to be sent with your certificate request
A challenge password []:demo6
An optional company name []:
```

6- generate the self signed certificate:

> openssl x509 -req -days 365 -in demo6.csr -signkey demo6.key -out demo6.crt

output:
```
Certificate request self-signature ok
subject=C = CL, ST = Valparaiso, L = Vina del mar, O = Internet Widgits Pty Ltd
```

7- list files in ssl:
> ls -la

```
-rwxrwxrwx 1 sebastian sebastian 1188 Jan 26 00:05 demo6.crt
-rwxrwxrwx 1 sebastian sebastian 1017 Jan 26 00:00 demo6.csr
-rwxrwxrwx 1 sebastian sebastian 1708 Jan 25 23:59 demo6.key
```

We will use the crt and the key file.

### Time to configure the virtual host:

Having apache server installed, go to /etc/apache2/sites-enabled

You can edit the existing file or you can create a new file with ".conf" extension, for example: my_vhost.conf

Add the vhost config to the file:

Here I included 2 vhosts:

* The first one is for http protocol, no ssl usage, no reverse proxy

* The second one is for https protocol, uses ssl files, uses reverse proxy

My System is Windows, I use linux with WSL2, I like to use Debian distro, that is why you will se the path as "/mnt/c"

```
<VirtualHost *:80>
    ServerName thruway_examples.localdev
    ErrorLog "/mnt/c/DEV/www/thruway_examples/error.log"

    DocumentRoot "/mnt/c/DEV/www/thruway_examples"
    <Directory "/mnt/c/DEV/www/thruway_examples">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>

<VirtualHost *:443>
    ServerName thruway_examples.localdev
    ErrorLog "/mnt/c/DEV/www/thruway_examples/error.log"

    # change the path to your ssl directory
    SSLEngine on
    SSLCertificateFile /home/sebastian/certs/demo6.crt
    SSLCertificateKeyFile /home/sebastian/certs/demo6.key

    DocumentRoot "/mnt/c/DEV/www/thruway_examples"
    <Directory /mnt/c/DEV/www/thruway_examples>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    # this should be read as: any url ending in "/thruway" gets piped as "ws://127.0.0.1:9090"
    ProxyPass /thruway ws://127.0.0.1:9090
    ProxyPassReverse /thruway ws://127.0.0.1:9090
</VirtualHost>
```

---
### Lets install mod proxy

enable the following apache modules:

```
sudo a2enmod proxy
sudo a2enmod proxy_http
sudo a2enmod proxy_wstunnel
```

Do not forget to restart the apache server so it takes the changes:
```
sudo service apache2 restart
```

---
### Don't forget the hosts file

In my case, I have to edit the following file:

C:\Windows\System32\drivers\etc\hosts

To do so, I had to open the windows notepad app with admin rights (right click, open as admin)

click ope n file and go to C:\Windows\System32\drivers\etc dir, then select type of file: all

you will see the hosts file.

Add the ServerName of your vhosts, in my case it is:

> ServerName thruway_examples.localdev

So I need to add:

```
127.0.0.1   thruway_examples.localdev
::1         thruway_examples.localdev
```
---
### install some dependencies:

the following will install a thruway client I will be using to produce the publishing to the topic "demo6"

> composer require rx/thruway-client

check out this issue I started, if you have any issue with this package:

https://github.com/voryx/RxThruwayClient/issues/18

it basically says that you need to use specific version of dependencies since recent commits to those dependencies will brake thruway RX client.

do the following if you run into that problem:
```
composer require voryx/event-loop 3.0.2
composer require rect/dns 1.11.0
composer require react/dns 1.11.0
composer require react/promise 2.10.0
composer require react/promise-stream 1.6.0
composer require react/socket 1.14.0
composer require rx/websocket 2.1.8
composer require symfony/http-foundation 6.3.8
composer require symfony/routing 6.3.5
```
---
### Test the demo6:

You should be ready, just launch the router and go to demo6 in the /index.php

the browser client should connect using the url "wss://thruway_examples.localdev/thruway"

you will also need to start the other client "DEMO6/bin/otherInternalClient.php"

In order to use the same terminal, try the following:

> php bin/router.php &

> php bin/otherInternalClient.php &

To stop both in the same terminal:

> jobs

you will see two jobs, kill them:

> kill %2

> kill %1

or use ps:

> ps aux

see the pid column (2nd column), kill them (be careful not to kill any important process):

> kill pid

