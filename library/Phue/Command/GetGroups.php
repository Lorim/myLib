<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */

/**
 * Get groups command
 */
class Phue_Command_GetGroups implements Phue_Command_CommandInterface
{
    /**
     * Send command
     *
     * @param Client $client Phue Client
     *
     * @return array List of Group objects
     */
    public function send(Phue_Client $client)
    {
        // Get response
        $response = $client->getTransport()->sendRequest(
            $client->getUsername()
        );

        // Return empty list if no groups
        if (!isset($response->groups)) {
            return [];
        }

        $groups = [];

        foreach ($response->groups as $groupId => $attributes) {
            $groups[$groupId] = new Phue_Group($groupId, $attributes, $client);
        }

        return $groups;
    }
}
