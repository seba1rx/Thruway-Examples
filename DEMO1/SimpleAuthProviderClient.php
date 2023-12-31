<?php

namespace DEMO1;

/**
 * Class SimpleAuthProviderClient
 */
class SimpleAuthProviderClient extends \Thruway\Authentication\AbstractAuthProviderClient
{

    /**
     * @return string
     */
    public function getMethodName()
    {
        return 'simplysimple';
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

        if ($signature == "letMeIn") {
            return ["SUCCESS", (object)[]];
        } else {
            return ["FAILURE"];
        }

    }

}
