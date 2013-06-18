<?php

/*
 * A simple ZeroMQ client for encoding a URL to a hash
 */

$context = new ZMQContext();

//Socket to talk to server
$requester = new ZMQSocket($context, ZMQ::SOCKET_REQ);
$requester->connect("tcp://twayd.com:5556");

$requester->send("map.google.com");
$string = $requester->recv();
printf ("Received reply [%s]%s", $string, PHP_EOL);
?>
