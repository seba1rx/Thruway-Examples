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
    // url: 'ws://demo.thruway.ws:9090/',
    url: 'ws://127.0.0.1:9090/',
    realm: 'somerealm',
    onchallenge: onchallenge,
    authmethods: ['simplysimple'],
  });

  connection.onopen = function(session) {
    console.log("Successfully made the socket connection. id: ", session._id);
    console.log("calling getfreeSpace and getMockData, please wait...");

    //call a remote procedure
    connection.session.call('com.example.getfreeSpace', []).then(
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

    // // demo, this won't work
    // connection.session.call('com.example.sendMail', ['demo@mail.com', 'my subject', 'my body']).then(
    //   function(res) {
    //     console.log("Result:", res);
    //   },
    //   function(error) {
    //     console.log("RPC Call Failure: " + error);
    //   }
    // );

  };

  connection.onclose = function (reason, details) {
     console.log("Connection close: " + reason);
  };

  connection.open();
