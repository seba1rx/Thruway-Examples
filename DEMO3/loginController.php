<?php

include_once __DIR__ . "/../vendor/autoload.php";

use MockModel\MyMockModel;
use Rx\SessionAdmin;
use Thruway\Connection;
use Thruway\ClientSession;
use Thruway\Logging\Logger;
use Psr\Log\NullLogger;



$sessionAdmin = new SessionAdmin();
$sessionAdmin->activateSession(); // like session_start()

$response = [
    "is_ok" => false,
    "msg" => null,
    "user_id" => null,
];

if(
    isset($_POST["user"])
    && isset($_POST["password"])
    && !empty(trim($_POST["user"]))
    && !empty(trim($_POST["password"]))
){
    $user_id = MyMockModel::checkCredentials($_POST["user"], $_POST["password"]);
    if($user_id === false){
        $response["is_ok"] = false;
        $response["msg"] = "check credentials";
    }else{

        /**************************************************************************/
        // step 1: check if there is a connection using this credentials ($user_id)

        $is_connected = false;
        $debug = [];

        Logger::set(new NullLogger()); // shush the logger so it doesn't pollute the output of this example

        $onClose = function ($msg) {
            // echo $msg;
        };

        $onChallenge = function($method){

            // if($method == 'simplysimple'){
            //     return "letMeIn";
            // }

            return "letMeIn";
        };

        $connection = new Connection(
            [
                "realm"   => 'somerealm',
                "onClose" => $onClose,
                "url"     => 'ws://127.0.0.1:9090',
                "onChallenge" => $onChallenge,
                "authmethods" => ['simplysimple'],
                "max_retries" => 0,
            ]
        );

        try{

            $connection->on(
                'open',
                function (ClientSession $session) use ($connection, &$is_connected, &$debug, $user_id){
                    $debug[] = "on open";
                    $session->call('com.example.isTheUserConnected', [$user_id])->then(
                        function ($res) use ($connection, &$is_connected, &$debug){
                            $is_connected = $res[0]->is_connected;
                            $debug[] = "is_object: " . (int)is_object($is_connected);
                            $debug[] = "is_bool: " . (int)is_bool($is_connected);
                            $debug[] = "is_connected: " . (int)$is_connected;
                            $connection->close();
                        },
                        function ($error) use ($connection, &$is_connected, &$debug){
                            $debug[] = "on open error";
                            $connection->close();
                        }
                    );
                }
            );

            // TODO: test on error, on close, to handle event if server is down or impossible to connect
            $connection->on(
                'error',
                function () {
                    echo json_encode(["error" => "server is down or too much time to execute, sorry"]);
                }
            );

            $connection->open();

        }catch(\Exception $e){

            echo json_encode(["error" => "server is down or too much time to execute, sorry"]);
        }

        /**************************************************************************/
        // step 2: if there is no connection using the credentials, then create php session

        if(!$is_connected){

            $sessionAdmin->createUserSession($user_id);
            $url_to_add = ["welcome.php"];
            foreach($url_to_add as $url){
                if(!in_array($url, $_SESSION['allowedUrl'])){
                    $_SESSION['allowedUrl'][] = "welcome.php";
                }
            }
            $response["is_ok"] = true;
            $response["msg"] = "welcome";
            $response["user_id"] = $user_id;
            $response["debug"] = $debug;
        }else{
            $response["is_ok"] = false;
            $response["msg"] = "cannot access: credentials in use, please use  a different user and password";
            $response["user_id"] = $user_id;
            $response["debug"] = $debug;
        }
    }
}else{
    $response["msg"] = "please fill the user and password fields";
}

echo json_encode($response);