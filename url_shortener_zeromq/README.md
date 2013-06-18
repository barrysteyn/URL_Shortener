#URL_Shortener
This is the follow up to my URL shortener that uses REST as the communication protocol. Instead, this project will use [ZeroMQ](http://www.zeromq.org)

To see this in action, please go to [http://twayd.com](http://twayd.com).

##How It Works (Briefly)
The internal workings of how the URL is shortened is identical to the last project. 

The ZeroMQ pattern that was chosen was a ROUTER-DEALER broker pattern. This was chosen because it is:

 * Asynchronous
 * Load balanced
 * Easy to add additional worker nodes

###Technologies Used:

 1. **Server Scripting Language**: Php.
 2. **Socket Protocol**: ZeroMQ.
 2. **Server**: Linux ubuntu, hosted on Amazon AWS EC2.
 3. **Web Server**: Apache, mpm-prefork. 
 4. **Database Server**: Postgres. 

#ZeroMQ Details

##Host
`http://twayd.com`

##Ports Numbers

 1. `5555` - Hash decode service (takes a hash as input, and outputs the original URL).
 2. `5556` - URL encode service (takes an URL as input, and outputs a shortened hash).

##Example - Hash Decode Client

    <?php

    $context = new ZMQContext();

    //  Socket to talk to server
    $requester = new ZMQSocket($context, ZMQ::SOCKET_REQ);
    $requester->connect("tcp://twayd.com:5555");

    $requester->send("http://twayd/7");
    $string = $requester->recv();
    printf ("Received reply [%s]%s", $string, PHP_EOL);

    ?>

##Example - Url Encode Client

**Note:** Url Encode Worker will return the encoded hash in the database if it exists, else it will be created.

    <?php
    $context = new ZMQContext();

    //Socket to talk to server
    $requester = new ZMQSocket($context, ZMQ::SOCKET_REQ);
    $requester->connect("tcp://twayd.com:5556");

    $requester->send("http://hootsuite.com");
    $string = $requester->recv();
    printf ("Received reply [%s]%s", $string, PHP_EOL);
    ?>

