<?php

namespace DEMO3;

use MockModel\MyMockModel;

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
        echo "--------------- Hello from InternalClient ------------\n";
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
        return ["Free space: " . (string)disk_free_space('/')];
        // return [disk_free_space('c:')]; // use c: for you windowers
    }

    /**
     * example returning mock data
     */
    public function getMockData(): array
    {
        return MyMockModel::getMyMockData();
    }

    /**
     * Get online connections
     *
     * @return array
     */
    public function getOnline()
    {
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
        $marked = false;
        foreach($this->_sessions as $ws_id => $ws_data){
            if($ws_id == $args[1]){
                $this->_sessions[$ws_id]['user_id'] = $args[0];
                $marked = true;
                break;
            }
        }

        if($marked){
            return [
                "ws_session_data" => $this->_sessions[$ws_id]
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
        $data = self::toArray($args);
        $ws_session_id = $data[0]["session"];
        $realm = $data[0]["realm"];

        echo "Session {$data[0]['session']} joinned\n";
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
        $data = self::toArray($args);
        $ws_session_id = $data[0]["session"];

        if (!empty($ws_session_id)) {

            foreach($this->_sessions as $ws_session_id => $ws_session_data){
                if($ws_session_id == $ws_session_data["ws_session_id"]){
                    echo "Session {$ws_session_id} leaved\n";
                    unset ($this->_sessions[$ws_session_id]);
                    return;
                }
            }
        }
    }

    private static function toArray($object)
    {
        return json_decode(json_encode($object), true);
    }
}