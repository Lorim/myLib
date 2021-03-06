<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */

/**
 * Set group attributes command
 */
class Phue_Command_SetGroupAttributes implements Phue_Command_CommandInterface
{
    /**
     * Group Id
     *
     * @var string
     */
    protected $groupId;

    /**
     * Group attributes
     *
     * @var array
     */
    protected $attributes = [];

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
     * Set name
     *
     * @param string $name Name
     *
     * @return Group Self object
     */
    public function name($name)
    {
        $this->attributes['name'] = (string) $name;

        return $this;
    }

    /**
     * Set lights
     *
     * @param array $lights List of light Ids or Light objects
     *
     * @return Group Self object
     */
    public function lights(array $lights)
    {
        $lightList = [];

        foreach ($lights as $light) {
            $lightList[] = (string) $light;
        }

        $this->attributes['lights'] = $lightList;

        return $this;
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
            Phue_Transport_TransportInterface::METHOD_PUT,
            (object) $this->attributes
        );
    }
}
