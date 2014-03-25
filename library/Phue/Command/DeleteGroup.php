<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */

/**
 * Delete group command
 */
class Phue_Command_DeleteGroup implements Phue_Command_CommandInterface
{
    /**
     * Group Id
     *
     * @var string
     */
    protected $groupId;

    /**
     * Constructs a command
     *
     * @param mixed $group Group Id or Group object
     */
    public function __construct($group)
    {
        $this->groupId = (string) $group;
    }

    /**
     * Send command
     *
     * @param Client $client Phue Client
     */
    public function send(Phue_Client $client)
    {
        $client->getTransport()->sendRequest(
            "{$client->getUsername()}/groups/{$this->groupId}",
            Phue_Transport_TransportInterface::METHOD_DELETE
        );
    }
}
