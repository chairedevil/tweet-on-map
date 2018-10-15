<?php
    require_once ('../vendor/autoload.php');
    use Abraham\TwitterOAuth\TwitterOAuth;

    class getTweet{

        private $consumer_key = "CG7i3j2bl0eYIv99vjMSvbY1I";
        private $consumer_secret = "RULVj5gepeAgg39xgRpR53F1aNILj2qOJhaAdGA8alnNHVBHhG";
        private $access_token = "62762346-tARVF3nuh0Uuul6834tw4gmr8prKwREBOoSiv73mH";
        private $access_token_secret = "EoTp7Evg15OCW04zMYshyK8h4FifhB3SXfaYNMfRKlZWy";

        public function onLocation($lat, $lng){

            $connection = new TwitterOAuth($this->consumer_key, $this->consumer_secret, $this->access_token, $this->access_token_secret);
            $content = $connection->get("account/verify_credentials");

            if ($connection->getLastHttpCode() == 200) {

                $results = $connection->get("search/tweets", ["geocode" => "${lat},${lng},1km", "count" => 100, "result_type" => "recent"]);
                return json_encode($results);
        
            }

        }

        public function userTimeline($userId){
            $connection = new TwitterOAuth($this->consumer_key, $this->consumer_secret, $this->access_token, $this->access_token_secret);
            $content = $connection->get("account/verify_credentials");

            if ($connection->getLastHttpCode() == 200) {

                $results = $connection->get("statuses/user_timeline", ["screen_name" => $userId, "count" => 100]);
                return json_encode($results);
        
            }
        }
    }