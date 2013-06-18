<?php

include "../config/core.php";
include "../config/dbConfig.php";
include "../shared_logic/UrlEncode.php";

//Given a url, obtain/create the hash
function urlHash($url) {
    $urlEncode = new UrlEncode();
    $url = $urlEncode->returnUrl($url);
    $result = array();
    global $HOST;

    if (!$url) {
        $result["error"] = "No input given";
    } else {
        //Database stuff
        global $dbConnStr; //This comes from dbConfig.php
        $dbConnection = pg_connect($dbConnStr);

        //First try get the id
        $query = sprintf("SELECT id FROM urls WHERE url = '%s'",$url); //sprintf to reduce SQL injection attack
        $dbResult = pg_query($dbConnection, $query);
        $row = pg_fetch_assoc($dbResult);
        
        //The URL does not exist in the DB, so insert it 
        if (!$row) {
            $query = sprintf("INSERT INTO urls(url) VALUES('%s') RETURNING id",$url); 
            $dbResult = pg_query($dbConnection, $query);
            $row = pg_fetch_assoc($dbResult);
        }

        if ($row) {
            $shortenedUrl = $urlEncode->encodeToShortenedUrl($row["id"]);
            $result["hashedUrl"] = "http://{$HOST}/{$shortenedUrl}";
        } else {
            $result["error"] = "Unspecified error: Please email barry.steyn@gmail.com with details";
        }
    }

    return json_encode($result);
}

//ZeroMQ Worker Thread
function url_hash_worker() {
    // Socket to talk to dispatcher
    $context = new ZMQContext();
    $receiver = new ZMQSocket($context, ZMQ::SOCKET_REP);
    $receiver->connect("ipc://urlencode.ipc");

    while (true) {
        $url = $receiver->recv();
        $receiver->send(urlHash($url));        
    }
}

//Launch pool of worker threads
for ($thread_nbr = 0; $thread_nbr != $ENCODEWORKERS; $thread_nbr++) {
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
$clients->bind("tcp://*:5556");

//Socket to talk to workers
$workers = new ZMQSocket($context, ZMQ::SOCKET_DEALER);
$workers->bind("ipc://urlencode.ipc");

//Connect work threads to client threads via a queue
$device = new ZMQDevice($clients, $workers);
$device->run();

?>
