<?php

namespace DEMO3;

use MockModel\MyMockModel;
use Thruway\Logging\Logger;

/**
 * Class InternalClient3 based on Examples/MetaEvent/InternalClient.php
 *
 * this is the same as internal client 1 but has metaevents listeners
 */
class InternalClient3 extends \Thruway\Peer\Client
{
    /**
     * List sessions info
     *
     * @var array
     */
    protected $_sessions = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct("somerealm");
    }

    /**
     * @param \Thruway\ClientSession $session
     * @param \Thruway\Transport\TransportInterface $transport
     */
    public function onSessionStart($session, $transport)
    {
        Logger::debug($this, "--------------- Hello from InternalClient ------------");
        Logger::debug($this, "registering getphpversion");
        Logger::debug($this, "registering getonline");
        Logger::debug($this, "registering getfreespace");
        Logger::debug($this, "registering getMockData");
        Logger::debug($this, "registering isTheUserConnected");
        Logger::debug($this, "registering ws_login");
        Logger::debug($this, "listenint for wamp.metaevent.session.on_join events");
        Logger::debug($this, "listening for wamp.metaevent.session.on_leave events");

        $session->register('com.example.getphpversion', [$this, 'getPhpVersion']);
        $session->register('com.example.getonline',     [$this, 'getOnline']);
        $session->register('com.example.getfreespace', [$this, 'getFreeSpace']);
        $session->register('com.example.getMockData', [$this, 'getMockData']);
        $session->register('com.example.isTheUserConnected', [$this, 'isTheUserConnected']);
        $session->register('com.example.ws_login', [$this, 'ws_login']);

        $session->subscribe('wamp.metaevent.session.on_join',  [$this, 'onSessionJoin']);
        $session->subscribe('wamp.metaevent.session.on_leave', [$this, 'onSessionLeave']);
    }

    /**
     * Handle get PHP version
     *
     * @return array
     */
    public function getPhpVersion()
    {
        return [phpversion()];
    }

    /**
     * @return array
     */
    public function getFreeSpace()
    {
        Logger::debug($this, "internal client: managing RPC getFreeSpace");
        return ["Free space: " . (string)disk_free_space('/')];
        // return [disk_free_space('c:')]; // use c: for you windowers
    }

    /**
     * example returning mock data
     */
    public function getMockData(): array
    {
        Logger::debug($this, "internal client: managing RPC getMockData");
        return MyMockModel::getMyMockData();
    }

    /**
     * Get online connections
     *
     * @return array
     */
    public function getOnline()
    {
        Logger::debug($this, "internal client: managing RPC getOnline");
        return [$this->_sessions];
    }

    /**
     * looks for a user_id in $this->_sessions
     *
     * @param array $args
     * @return array
     */
    public function isTheUserConnected($args)
    {
        $is_connected = false;
        if(isset($args[0]) && is_int($args[0])){
            foreach($this->_sessions as $ws_id => $ws_data){
                if($ws_data['user_id'] == $args[0]){
                    $is_connected = true;
                    break;
                }
            }
        }

        return ["is_connected" => $is_connected];
    }

    /**
     * fills the $this->_sessions user_id null field set in onSessionJoin with the user_id
     *
     * @param array $args
     * @return array
     */
    public function ws_login($args)
    {
        Logger::debug($this, "internal client: managing RPC ws_login, args: " . json_encode($args));
        $marked = false;
        $array_args = self::toArray($args[0]);
        foreach($this->_sessions as $ws_id => $ws_data){
            if($ws_id == $array_args['ws_session_id']){
                $this->_sessions[$ws_id]['user_id'] = $array_args['user_id'];
                $this->_sessions[$ws_id]['user_data'] = MyMockModel::getUserById($array_args['user_id']);
                $marked = true;
                break;
            }
        }

        if($marked){
            return [
                "ws_session_data" => $this->_sessions[$ws_id],
                "callback" => [
                    "is_rpc" => true,
                    "method" => "com.example.getonline",
                    //"args" => [],
                ],
            ];
        }else{
            // something went wrong
            return [
                "error" => "session not found",
                "ws_session_data" => [],
            ];
        }
    }

    /**
     * Handle on new session joinned
     *
     * @param array $args
     * @param array $kwArgs
     * @param array $options
     * @return void
     * @link https://github.com/crossbario/crossbar/wiki/Session-Metaevents
     */
    public function onSessionJoin($args, $kwArgs, $options)
    {
        Logger::debug($this, "internal client: event onSessionJoin");
        $data = self::toArray($args);
        $ws_session_id = $data[0]["session"];
        $realm = $data[0]["realm"];

        Logger::debug($this, "Session {$data[0]['session']} joinned");
        $this->_sessions[$ws_session_id] = [
            "ws_session_id" => $ws_session_id,
            "realm" => $realm,
            "user_id" => null, // to be filled later on when RPC call to ws_login() is done
        ];
    }

    /**
     * Handle on session leaved
     *
     * @param array $args
     * @param array $kwArgs
     * @param array $options
     * @return void
     * @link https://github.com/crossbario/crossbar/wiki/Session-Metaevents
     */
    public function onSessionLeave($args, $kwArgs, $options)
    {
        Logger::debug($this, "internal client: event onSessionLeave");
        $data = self::toArray($args);
        $ws_session_id = $data[0]["session"];

        if (!empty($ws_session_id)) {

            foreach($this->_sessions as $_session_id => $_session_data){
                if($ws_session_id == $_session_id){
                    Logger::debug($this, "Session {$ws_session_id} leaved");
                    unset ($this->_sessions[$ws_session_id]);
                    return;
                }
            }
        }
    }

    /**
     * @param object|string $object
     */
    private static function toArray($object)
    {
        return json_decode(json_encode($object), true);
    }
}