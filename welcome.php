<?php

include_once __DIR__ . "/vendor/autoload.php";

use Rx\SessionAdmin;

$sessionAdmin = new SessionAdmin();
$sessionAdmin->activateSession(); // like session_start()

if(!$_SESSION['urlIsAllowedToLoad']){
    header('Location: metaevents.php');
}

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
        <h1>you are in welcome</h1>
        <h3>only logged users can see this page</h3>
    </div>
    <div style="margin: 20px;">
        <button type="button" class="btn btn-primary ml-3" onclick="location.href = 'exit.php'">Logout</button>
    </div>
    <div style="margin: 20px;">The $_SESSION data:</div>
    <div style="margin: 20px;"><?php echo "<pre>" . json_encode($_SESSION, JSON_PRETTY_PRINT) . "</pre>";?></div>


    <script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/autobahn.min.js"></script>
    <script src="js/simpleBrowserClient.js"></script>
    <script>
        connect();
    </script>
</body>
</html>