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

  <script>
    function callHandler() {

      $.ajax({
        url: '/bin/launchInternalHandler.php',
        async: false,
        success: function (response) {
          console.log('respuesta: ' + response);
        }
      });

    }
  </script>
</body>

</html>