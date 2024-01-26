function onchallenge(session, method, extra) {
  console.log("onchallenge method: " + method);
  console.log('**********************************************************');
  console.log("authenticating into realm using method '" + method + "'");
  console.log(extra);
  return 'letMeIn';
}

var connection;
var my_session;

// var url_to_use = 'ws://127.0.0.1:9090/'; // if the page is using http to load
var url_to_use = "wss://thruway_examples.localdev/thruway"; // if the page is using https to load
console.log("trying to connect using the url " + url_to_use);

connection = new autobahn.Connection({
  url: url_to_use,
  realm: 'somerealm',
  onchallenge: onchallenge,
  authmethods: ['simplysimple'],
});

connection.onopen = function(session){
  console.log("Successfully made the socket connection.", session._id);

  my_session = session;

  makeRPC('getphpversion', []);
  subscribeToTopic("demo6");

};

connection.onclose = function (reason, details) {
    console.log("Connection close: " + reason);
};

connection.open();

// ##########################################
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


/**
 * The client subscribed to a topic
 *
 * @param {String} newTopic
 */
function subscribeToTopic(newTopic) {
    try {
        console.log("subscribing to topic " + newTopic);
        my_session.subscribe(newTopic, subscribedEvent); //subscribedEvent is the callback handler for subscription events
    } catch (e) {
        console.log("An error ocurred when trying to subscribe to the topic '" + newTopic + "'");
    }
}


/**
 * this is the session.subscribe callback handler, to handle events on the subscribed topic
 * when subscribed to a topic, this function will handle any event happening
 *
 * @param {Array} args
 * @param {Object} kwargs
 * @param {Object} details
 */
function subscribedEvent(args, kwargs, details) {
    console.log("Got subscription event on topic " + details.topic + ": ", JSON.parse(args));
}


/**
 * Unsubscribe from topic
 *
 * @param {String} topic
 */
function unsubscribeFromTopic(topic){
    try{
        my_session.unsubscribe(topic).then(
            function (gone) {
                console.log('successfully unsubscribed from ' + topic);
            },
            function (error) {
                console.log('could not unsubscribe from ' + topic);
            }
        );
    }catch(e){
        console.log("An error ocurred when trying to unsubscribe from topic '" + topic + "'");
    }
}


/**
 * Publish a String|object to the topic
 *
 * session.publish('com.myapp.hello', ['Hello, world!'], {}, {exclude_me: false});
 *
 * @param {String} topic
 * @param {Object} data
 * @param {bool} excludeMe
 */
function publish(topic, data, excludeMe = false) {
    try {
        console.log("publishing to topic '" + topic + "' the content: " + data);
        my_session.publish(topic, [data], {}, excludeMe);
    } catch (e) {
        console.log("An error occurred when trying to publish to the topic '" + topic + "'");
    }
}
