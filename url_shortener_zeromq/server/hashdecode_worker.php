<?php

/*
 * Will return the original URL (if it exists)
 */

include "../config/dbConfig.php";
include "../shared_logic/UrlEncode.php";

//Given a hash, obtain the original url
function hashDecode($hashedUrl) {
    $urlEncode = new UrlEncode();
    $result = array();

    //No hash was given as input
    if (!$hashedUrl) {
        $result["error"] = "No input given";
    } else {
        $hashedUrl = $urlEncode->parseHash($hashedUrl);
        if ($hashedUrl != -1) {
            $id = $urlEncode->decodeToOriginalUrl($hashedUrl);

            //Database stuff
            global $dbConnStr; //This comes from dbConfig.php
            $dbConnection = pg_connect($dbConnStr);
            $query = sprintf("SELECT url FROM urls WHERE id = %d",$id); //sprintf to reduce SQL injection attack
            $dbResult = pg_query($query);
            $row = pg_fetch_assoc($dbResult);

            if ($row) {
                $result["originalUrl"] = $row["url"];
            } else {
                //The URL does not exist in our database
                $result["error"] = "Url does not exist: {$hashedUrl}";
            }
        } else {
            $result["error"] = "Hashed url host incorrect";
        }
    }

    return json_encode($result);
}


//ZeroMQ Worker Thread
function url_hash_worker() {
    // Socket to talk to dispatcher
    $context = new ZMQContext();
    $receiver = new ZMQSocket($context, ZMQ::SOCKET_REP);
    $receiver->connect("ipc://workers.ipc");

    while (true) {
        $hashedUrl = $receiver->recv();
        $receiver->send(hashDecode($hashedUrl));        
    }
}

//Launch pool of worker threads
for ($thread_nbr = 0; $thread_nbr != 1; $thread_nbr++) {
    $pid = pcntl_fork();
    if ($pid == 0) {
        url_hash_worker();
        exit();
    }
}

//Prepare our context and sockets
$context = new ZMQContext();

//Socket to talk to clients
$clients = new ZMQSocket($context, ZMQ::SOCKET_ROUTER);
$clients->bind("tcp://*:5555");

//Socket to talk to workers
$workers = new ZMQSocket($context, ZMQ::SOCKET_DEALER);
$workers->bind("ipc://workers.ipc");

//Connect work threads to client threads via a queue
$device = new ZMQDevice($clients, $workers);
$device->run();

?>
