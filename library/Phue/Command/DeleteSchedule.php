<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */

/**
 * Delete schedule command
 */
class Phue_Command_DeleteSchedule implements Phue_Command_CommandInterface
{
    /**
     * Schedule Id
     *
     * @var string
     */
    protected $scheduleId;

    /**
     * Constructs a command
     *
     * @param mixed $schedule Schedule Id or Schedule object
     */
    public function __construct($schedule)
    {
        $this->scheduleId = (string) $schedule;
    }

    /**
     * Send command
     *
     * @param Client $client Phue Client
     */
    public function send(Phue_Client $client)
    {
        $client->getTransport()->sendRequest(
            "{$client->getUsername()}/schedules/{$this->scheduleId}",
            Phue_Transport_TransportInterface::METHOD_DELETE
        );
    }
}
