<!DOCTYPE html>
<html>

<head>
  <script src="js/autobahn.min.js">
  </script>
  <script src="js/script.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>
  Open the console to see the messages.

  <br>
  <br>
  <br>

  <button type="button" onclick="runConnectionObjectUsingAjax();"> Call connection object using ajax</button>
  <br>
  <br>
  <button type="button" onclick="location.href = 'metaevents.php';">Go to DEMO3</button>
  <br>
  <br>
  <button type="button" onclick="location.href = 'chatroom.php';">Go to DEMO4</button>
  <br>
  <br>
  <button type="button" onclick="location.href = 'wss_with_reverse_proxy.php';">Go to DEMO6</button>

  <script>
    function runConnectionObjectUsingAjax() {

      console.log('calling DEMO2/bin/launchInternalClientAsConnectionObject.php using jquery ajax, please wait...');
      console.log('this will also work for DEMO1');
      $.ajax({
        url: 'DEMO2/bin/launchInternalClientAsConnectionObject.php',
        // async: false,
        dataType: 'json',
        success: function (response) {
          console.log('response: ', response);
        }
      });
    }
  </script>
</body>

</html>