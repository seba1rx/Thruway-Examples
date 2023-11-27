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

  my_session = session;

  makeRPC('com.example.getphpversion', []);
  makeRPC('com.example.getonline', []);
  makeRPC('com.example.getfreespace', []);
  makeRPC('com.example.getMockData', []);
  /**
   * RPC can take args as array list, but cannot have associative arrays
   */
  // makeRPC('com.example.ws_login', [user_id, session._id]); // 2 items in agrs
  /**
   * to solve this you can pass a json string as an argument item
   */
  makeRPC('com.example.ws_login', [{'user_id': user_id, 'ws_session_id': session._id}]); // 1 item in args
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
  if(rpc_args != null){
    console.log("making RPC: " + rpc_method + " using args: " + JSON.stringify(rpc_args));
  }else{
    console.log("making RPC: " + rpc_method);
  }
  my_session.call(rpc_method, rpc_args).then(
  // connection.session.call(rpc_method, rpc_args).then(
    function(res) {
      console.log("Result to " + rpc_method + ":", res);
      if(res.hasOwnProperty("callback")){
        console.log("RPC " + rpc_method + " has callback");

        if(res.callback.is_rpc){
          if(res.callback.hasOwnProperty("args")){
            makeRPC(res.callback.method, res.callback.args);
          }else{
            makeRPC(res.callback.method);
          }
        }else{
          if(res.callback.hasOwnProperty("args")){
            eval(res.callback.method + "("+ res.callback.args + ")");
          }else{
            eval(res.callback.method + "()");
          }
        }
      }
      // return "Result:", res;
    },
    function(error) {
      // return "RPC Call Failure: " + error.error;
      console.log("RPC Call to " + rpc_method + "Failure: " + error.error);
    }
  );
}

function connect(the_user_id){
  user_id = the_user_id;
  connection.open();
}
