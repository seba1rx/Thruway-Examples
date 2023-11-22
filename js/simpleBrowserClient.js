try {
    // for Node.js
    var autobahn = require('autobahn');
} catch (e) {
    // for browsers (where AutobahnJS is available globally)
}

function onchallenge(session, method, extra) {
  console.log("onchallenge method: " + method);
  console.log('**********************************************************');
  console.log("authenticating into realm using method '" + method + "'");
  console.log(extra);
  return 'letMeIn';
}

var connection = new autobahn.Connection({
    url: 'ws://127.0.0.1:9090/',
    realm: 'somerealm',
    onchallenge: onchallenge,
    authmethods: ['simplysimple'],
  });

  connection.onopen = function(session) {
    console.log("Successfully made the socket connection.");

    //call a remote procedure
    connection.session.call('com.example.getphpversion', []).then(
      function(res) {
        console.log("Result:", res);
      },
      function(error) {
        console.log("RPC Call Failure: " + error.error);
      }
    );

    connection.session.call('com.example.getonline', []).then(
      function(res) {
        console.log("Result:", res);
      },
      function(error) {
        console.log("RPC Call Failure: " + error.error);
      }
    );

    connection.session.call('com.example.getfreespace', []).then(
      function(res) {
        console.log("Result:", res);
      },
      function(error) {
        console.log("RPC Call Failure: " + error.error);
      }
    );

    connection.session.call('com.example.getMockData', []).then(
      function(res) {
        console.log("Result:", res);
      },
      function(error) {
        console.log("RPC Call Failure: " + error.error);
      }
    );
  };

  connection.onclose = function (reason, details) {
     console.log("Connection close: " + reason);
  };

  connection.open();
