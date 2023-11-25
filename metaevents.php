<?php

include_once __DIR__ . "/vendor/autoload.php";

use MockModel\MyMockModel;
use Rx\SessionAdmin;

$conf = [
    "sessionName" => "thruway_demo_",
    "allowedURLs" => ["metaevents.php"],
    "keys" => [
        "some_key" => "some_value",
    ],
];
$sessionAdmin = new SessionAdmin($conf);
$sessionAdmin->activateSession(); // like session_start()

$users = MyMockModel::showUsers();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div style="margin: 20px;">
        <h1>you are in login</h1>
        <h3>Anyone can see this page</h3>
        <h3>if successfully logged in, you will be sent to wellcome page</h3>
    </div>
    <div style="margin: 20px;">
        <input type="text" name="user" id="user" value="anna">
        <input type="password" name="password" id="password" value="34567">
        <input type="button" value="login" onclick="login($('#user').val(), $('#password').val())" >
    </div>
    <div style="margin: 20px; width:40%; float: left;"><p>These are the users for testing, you can modify this list in MockModel/MyMockModel.php</p><?php echo "<pre>" . json_encode($users, JSON_PRETTY_PRINT) . "</pre>";?></div>
    <div style="margin: 20px; width:40%; float: right;"><p>The $_SESSION data:</p><?php echo "<pre>" . json_encode($_SESSION, JSON_PRETTY_PRINT) . "</pre>";?></div>

    <script src="js/jquery-3.7.1.min.js"></script>
    <script>

        function login(user, password){

        var wamp_host_name = "www.thruway_examples.local";
        // var wamp_host_name = "127.0.0.1";

        $.ajax({
            url: 'http://' + wamp_host_name + '/Demo/loginController.php',
            type: 'POST',
            dataType: 'json',
            data: {
                user: user,
                password:password,
            },
            success: function (response) {
            console.log("loginController: ", response);
            if(response.is_ok){

                console.log('credentials are ok');
                user_id = response.user_id;
                location.href = 'welcome.php';

                }else{
                    alert(response.msg);
                    console.log(response.msg);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(textStatus);
                console.log(textStatus);
            }
        });
        }
    </script>
</body>
</html>