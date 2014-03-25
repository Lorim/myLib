<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */


/**
 * Get group by id command
 */
class Phue_Command_GetGroupById implements Phue_Command_CommandInterface
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
     * @param int $groupId Group Id
     */
    public function __construct($groupId)
    {
        $this->groupId = (int) $groupId;
    }

    /**
     * Send command
     *
     * @param Client $client Phue Client
     *
     * @return Group Group object
     */
    public function send(Phue_Client $client)
    {
        // Get response
        $attributes = $client->getTransport()->sendRequest(
            "{$client->getUsername()}/groups/{$this->groupId}"
        );

        return new Phue_Group($this->groupId, $attributes, $client);
    }
}
