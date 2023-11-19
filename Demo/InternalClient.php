<?php

namespace Demo;

use MockModel\MyMockModel;
// use MyApp\Models\MyModel;
// use MyApp\Mailer\MyMailer;

/**
 * this is the internal client 1
 *
 * here you should create methods that interface with your main app or query the loop object
 */
class InternalClient extends \Thruway\Peer\Client
{

    /**
     * @return array
     */
    public function getFreeSpace()
    {
        return ["Free space: " . (string)disk_free_space('/')]; // use c: for you windowers
        // return [disk_free_space('c:')]; // use c: for you windowers
    }

    /**
     * Example methods interfacing with your main app
     */
    // public function sendMail($args): array
    // {
    //     $someData = MyModel::getSomeData($args);
    //     return MyMailer::sendMailUsingThisData($someData);
    // }
    // public function getSomeDataFromApp(): array
    // {
    //         $someData = MyModel::getUsefulData();
    //         return $someData;
    // }

    /**
     * example returning mock data
     */
    public function getMockData(): array
    {
        return MyMockModel::getMyMockData();
    }

    /**
     * @param \Thruway\ClientSession $session
     * @param \Thruway\Transport\TransportInterface $transport
     */
    public function onSessionStart($session, $transport)
    {
        $session->register('com.example.getfreespace', [$this, 'getFreeSpace']);
        $session->register('com.example.getMockData', [$this, 'getMockData']);
        // $session->register('com.example.sendMail', [$this, 'sendMail']); //RCP example
        // $session->register('com.example.getSomeDataFromApp', [$this, 'getSomeDataFromApp']); //RCP example
    }

}

