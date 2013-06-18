<?php

/*
 * A simple ZeroMQ client for encoding a URL to a hash
 */

$context = new ZMQContext();

//Socket to talk to server
$requester = new ZMQSocket($context, ZMQ::SOCKET_REQ);
$requester->connect("tcp://localhost:5556");

$requester->send("asterix");
$string = $requester->recv();
printf ("Received reply %d [%s]%s", $request_nbr, $string, PHP_EOL);
?>
