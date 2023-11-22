<?php

namespace Demo;

/**
 * this is the internal client 2, this class should be used
 * to call RPC functions registered by the internal client 1
 *
 * this client when started with bin/launchInternalClient2.php
 * will use SimpleClientAuth.php to authenticate
 *
 * This implementation is not showing the getfreespace result in console, it just
 * shows the getMockData result (if you have an idea on how to fix this please let me know)
 */
class InternalClient2 extends \Thruway\Peer\Client
{

    private $mySession;
    private $myTransport;

    /**
     * @param \Thruway\ClientSession $session
     * @param \Thruway\Transport\TransportInterface $transport
     */
    public function onSessionStart($session, $transport)
    {
        $this->mySession = $session;
        $this->myTransport = $transport;

        $this->callRPCMethod('com.example.getfreespace');
        $this->callRPCMethod('com.example.getMockData');

    }

    private function callRPCMethod($RPC, $args = null)
    {
        return $this->mySession->call($RPC, $args);
    }

}

