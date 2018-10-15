<?php

    $processFlag = 0;
    if(isset($_GET['process'])){
        $processFlag = $_GET['process'];
    }

    require_once ('../vendor/autoload.php');
    use Abraham\TwitterOAuth\TwitterOAuth;

    define("CONSUMER_KEY", "CG7i3j2bl0eYIv99vjMSvbY1I");
    define("CONSUMER_SECRET", "RULVj5gepeAgg39xgRpR53F1aNILj2qOJhaAdGA8alnNHVBHhG");
    $access_token = "62762346-tARVF3nuh0Uuul6834tw4gmr8prKwREBOoSiv73mH";
    $access_token_secret = "EoTp7Evg15OCW04zMYshyK8h4FifhB3SXfaYNMfRKlZWy";

    $connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token, $access_token_secret);
    $content = $connection->get("account/verify_credentials");

    if ($connection->getLastHttpCode() == 200) {

        if($processFlag == "gettweetonlocate"){
            echo 'getTweetOnLocate';

            $results = $connection->get("search/tweets", ["geocode" => "37.781157,-122.398720,1mi", "count" => 100]);
            echo json_encode($results);
            //echo '<pre>';
            //print_r($results);
            //echo '</pre>';
        }

    }

    class getTweet{
        public function onLocation($lat, $lgn){

        }
    }