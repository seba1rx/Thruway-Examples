### Connect to the Wamp server using WSS protocol

(Disclaimer: this demo uses apache server but it is recommended to use nginx, this demo will show the apache server only)

For this you will need to configure:

* An apache server enabling mod-proxy
* A self signed certificate (at least)
* A virtual host in the apache server
* The host file (so you can have local dns)
* the autobahn-js connection

This demo will have a single realm called "demo6"

There will be an internal client publishing demo messages in the "demo6" topic,
so once you get connected and subscribed to that topic you should start to see those messages.
This is in order to prove everything is working fine.


---
## Lets start with the certificate:

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

## Time to configure the virtual host:

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
## Lets install mod proxy

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
## Don't forget the hosts file

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
# install some dependencies:

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
# Test the demo:

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

