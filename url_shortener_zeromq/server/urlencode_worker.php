<?php

include "../shared_logic/UrlEncode.php";

/*
 * Will obtain a URL's hash (if it exists)
 */
function url_hash_worker() {
    $urlEncode = new UrlEncode();

    //Connect to the database - Not happy about using a connection string like this
    $connString = "host=localhost port=5432 user=postgres dbname=postgres password=b8f9qd40";
    $dbConnection = pg_connect($connString);

    // Socket to talk to dispatcher
    $context = new ZMQContext();
    $receiver = new ZMQSocket($context, ZMQ::SOCKET_REP);
    $receiver->connect("ipc://workers.ipc");

    while (true) {
        $url = $receiver->recv();
        $query = sprintf("SELECT id FROM urls WHERE url = '%s'",$urlEncode->returnUrl($url)); //sprintf to reduce SQL injection attack
        $result = pg_query($query);

        // Send reply back to client
        while ($row = pg_fetch_assoc($result)) {
            $receiver->send($urlEncode->encodeToShortenedUrl($row['id']));
        }
        
    }
}

//  Launch pool of worker threads
for ($thread_nbr = 0; $thread_nbr != 1; $thread_nbr++) {
    $pid = pcntl_fork();
    if ($pid == 0) {
        url_hash_worker();
        exit();
    }
}

//  Prepare our context and sockets
$context = new ZMQContext();

//  Socket to talk to clients
$clients = new ZMQSocket($context, ZMQ::SOCKET_ROUTER);
$clients->bind("tcp://*:5555");

//  Socket to talk to workers
$workers = new ZMQSocket($context, ZMQ::SOCKET_DEALER);
$workers->bind("ipc://workers.ipc");

//  Connect work threads to client threads via a queue
$device = new ZMQDevice($clients, $workers);
$device->run ();

?>
