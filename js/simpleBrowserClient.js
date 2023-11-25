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

var connection;
var my_session;
var user_id;

connection = new autobahn.Connection({
  url: 'ws://127.0.0.1:9090/',
  realm: 'somerealm',
  onchallenge: onchallenge,
  authmethods: ['simplysimple'],
});

connection.onopen = function(session){
  console.log("Successfully made the socket connection.", session._id);

  // my_session = session;

  makeRPC('com.example.getphpversion', []);
  makeRPC('com.example.getonline', []);
  makeRPC('com.example.getfreespace', []);
  makeRPC('com.example.getMockData', []);
  makeRPC('com.example.ws_login', [user_id, session._id]);
};

connection.onclose = function (reason, details) {
    console.log("Connection close: " + reason);
};

/**
 * make Remote Procedure Call.
 *
 * @param {string} rpc_method
 * @param {array|null} rpc_args
 */
function makeRPC(rpc_method, rpc_args){
  // my_session.call(rpc_method, rpc_args).then(
  connection.session.call(rpc_method, rpc_args).then(
    function(res) {
      if(res.hasOwnProperty("callback")){
        eval(res.callback);
      }
      return "Result:", res;
    },
    function(error) {
      return "RPC Call Failure: " + error.error;
    }
  );
}

function connect(){
  connection.open();
}
