<?php
/*
* Hello World client
* Connects REQ socket to tcp://localhost:5559
* Sends "Hello" to server, expects "World" back
* @author Ian Barber <ian(dot)barber(at)gmail(dot)com>
*/

$context = new ZMQContext();

//  Socket to talk to server
$requester = new ZMQSocket($context, ZMQ::SOCKET_REQ);
$requester->connect("tcp://localhost:5555");

for ($request_nbr = 0; $request_nbr < 1; $request_nbr++) {
    $requester->send("7");
    $string = $requester->recv();
    printf ("Received reply %d [%s]%s", $request_nbr, $string, PHP_EOL);
}
echo "Finished\n";

?>
