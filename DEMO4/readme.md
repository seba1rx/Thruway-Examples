# DEMO4

### Demo Chat implementation, exploring PubSub

This is a demo implementation of a chatroom, it has:
* chatroom list to choose from
* multiple connections in sigle chatroom, messages are 1 to many
* private messages, this is just a chatroom with extra rules, so only 2 connections are allowed, messages are 1 to many but since there are only two connections in the room it works as 1 to 1.
* broadcast and emit messages to all connections in all chatrooms

#### How to run it:

**open 1 terminal and run the server:**

> php DEMO4/bin/router.php

**open as many browser tabs as you want, from index go to DEMO4, pretend you chat with as many people you want**
