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
