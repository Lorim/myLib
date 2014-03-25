<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */


/**
 * Get bridge command
 */
class Phue_Command_GetBridge implements Phue_Command_CommandInterface
{
    /**
     * Send command
     *
     * @param Client $client Phue Client
     *
     * @return Bridge Bridge object
     */
    public function send(Phue_Client $client)
    {
        // Get response
        $response = $client->getTransport()->sendRequest(
            "{$client->getUsername()}/config"
        );

        return new Phue_Bridge($response, $client);
    }
}
