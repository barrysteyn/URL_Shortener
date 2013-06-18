<?php

/*
 * A simple ZeroMQ client for decoding a hashed URL
 */

if (sizeof($argv) < 2) {
    echo "Please provide an URL to the script as an argument\n";
    exit(0);
}

$context = new ZMQContext();

//  Socket to talk to server
$requester = new ZMQSocket($context, ZMQ::SOCKET_REQ);
$requester->connect("tcp://twayd.com:5555");

$requester->send($argv[1]);
$string = $requester->recv();
printf ("Received reply [%s]%s", $string, PHP_EOL);

?>
