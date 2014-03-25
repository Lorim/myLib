<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */


/**
 * Get lights command
 */
class Phue_Command_GetLights implements Phue_Command_CommandInterface
{
    /**
     * Send command
     *
     * @param Client $client Phue Client
     *
     * @return array List of Light objects
     */
    public function send(Phue_Client $client)
    {
        // Get response
        $response = $client->getTransport()->sendRequest(
            $client->getUsername()
        );

        // Return empty list if no lights
        if (!isset($response->lights)) {
            return [];
        }

        $lights = [];

        foreach ($response->lights as $lightId => $attributes) {
            $lights[$lightId] = new Phue_Light($lightId, $attributes, $client);
        }

        return $lights;
    }
}
