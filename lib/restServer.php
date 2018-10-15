<?php
    require_once('tweetAPI.php');

    $processFlag = "none";
    if(isset($_GET['process'])){
        $processFlag = $_GET['process'];
    }
    if(isset($_GET['lat'], $_GET['lng'])){
        $lat = $_GET['lat'];
        $lng = $_GET['lng'];
    }
    if(isset($_GET['user'])){
        $userId = $_GET['user'];
    }

    try {

        if($processFlag == "gettweetonlocate"){
            $t = new getTweet();
            echo $t->onLocation($lat,$lng);
        }
        if($processFlag == "getusertimeline"){
            $t = new getTweet();
            echo $t->userTimeline($userId);
        }
        
    }catch(Exception $e) {
        echo 'Message: ' .$e->getMessage();
    }