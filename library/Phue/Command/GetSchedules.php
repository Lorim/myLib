<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */

/**
 * Get schedules command
 */
class Phue_Command_GetSchedules implements Phue_Command_CommandInterface
{
    /**
     * Send command
     *
     * @param Client $client Phue Client
     *
     * @return array List of Schedule objects
     */
    public function send(Phue_Client $client)
    {
        // Get response
        $response = $client->getTransport()->sendRequest(
            $client->getUsername()
        );

        // Return empty list if no schedules
        if (!isset($response->schedules)) {
            return [];
        }

        $schedules = [];

        foreach ($response->schedules as $scheduleId => $attributes) {
            $schedules[$scheduleId] = new Schedule($scheduleId, $attributes, $client);
        }

        return $schedules;
    }
}
