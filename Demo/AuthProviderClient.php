<?php

namespace Demo;

use Thruway\Logging\Logger;

class AuthProviderClient extends \Thruway\Authentication\AbstractAuthProviderClient
{

    /**
     * The signature the authentication method compares against when new connection is authenticating
     *
     * this is a custom var for this example
     *
     * @var string $signature
     */
    private $signature;


    /**
     * sets the signature the auth provider will use when new connection is authenticating
     *
     * this is a custom method for this example
     *
     * @param string $signature
     */
    public function setSignature(string $signature): void
    {
        $this->signature = $signature;
    }

    /**
     * @return string
     */
    public function getMethodName()
    {
        return 'simplysimple';
    }

    /**
     * Process HelloMessage
     *
     * @param array $args
     * @return array<string|array>
     */
    public function processHello(array $args)
    {
        Logger::debug($this, 'processHello args: ' . json_encode($args));

        // return ['FAILURE'];
        // return ['CHALLENGE', (object)['challenge' => new \stdClass(), 'challenge_method' => $this->getMethodName()]];

        return parent::processHello($args);

    }

    /**
     * Process Authenticate message
     *
     * @param mixed $signature
     * @param mixed $extra
     * @return array
     */
    public function processAuthenticate($signature, $extra = null)
    {
        if (strcmp($signature,$this->signature) === 0) {
            return ["SUCCESS", (object)[]];
        } else {
            return ["FAILURE"];
        }

    }

}
