<?php

/*
 * A simple ZeroMQ client for decoding a hashed URL
 */

$context = new ZMQContext();

//  Socket to talk to server
$requester = new ZMQSocket($context, ZMQ::SOCKET_REQ);
$requester->connect("tcp://twayd.com:5555");

$requester->send("http://twayd.com/7");
$string = $requester->recv();
printf ("Received reply [%s]%s", $string, PHP_EOL);

?>
