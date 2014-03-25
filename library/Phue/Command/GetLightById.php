<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */


/**
 * Get light by id command
 */
class Phue_Command_GetLightById implements Phue_Command_CommandInterface
{
    /**
     * Light Id
     *
     * @var string
     */
    protected $lightId;

    /**
     * Constructs a command
     *
     * @param int $lightId Light Id
     */
    public function __construct($lightId)
    {
        $this->lightId = (int) $lightId;
    }

    /**
     * Send command
     *
     * @param Client $client Phue Client
     *
     * @return Light Light object
     */
    public function send(Phue_Client $client)
    {
        // Get response
        $attributes = $client->getTransport()->sendRequest(
            "{$client->getUsername()}/lights/{$this->lightId}"
        );

        return new Phue_Light($this->lightId, $attributes, $client);
    }
}
