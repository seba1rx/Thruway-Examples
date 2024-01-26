<?php

namespace DEMO6;

use Thruway\Logging\Logger;

/**
 * Class InternalClient6 based on Examples/MetaEvent/InternalClient.php
 *
 *
*/
class InternalClient6 extends \Thruway\Peer\Client
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
    public function __construct($realm_name, \React\EventLoop\LoopInterface $loop)
    {
        parent::__construct($realm_name, $loop);
    }

    /**
     * @param \Thruway\ClientSession $session
     * @param \Thruway\Transport\TransportInterface $transport
     */
    public function onSessionStart($session, $transport)
    {
        Logger::debug($this, "--------------- Hello from InternalClient ------------");
        Logger::debug($this, "registering getphpversion");

        $session->register('getphpversion', [$this, 'getPhpVersion']);
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


}