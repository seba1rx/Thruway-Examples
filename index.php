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
    function runConnectionObjectUsingAjax() {

      console.log('calling bin/launchInternalClientAsConnectionObject.php using jquery ajax, please wait...');
      $.ajax({
        url: 'bin/launchInternalClientAsConnectionObject.php',
        // async: false,
        dataType: 'json',
        success: function (response) {
          console.log('respuesta: ', response);
        }
      });

    }
  </script>
</body>

</html>