<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */

/**
 * Delete user command
 */
class Phue_Command_DeleteUser implements Phue_Command_CommandInterface
{
    /**
     * Username
     *
     * @var string
     */
    protected $username;

    /**
     * Constructs a command
     *
     * @param mixed $username Username or User object
     */
    public function __construct($username)
    {
        $this->username = (string) $username;
    }

    /**
     * Send command
     *
     * @param Client $client Phue Client
     */
    public function send(Phue_Client $client)
    {
        $client->getTransport()->sendRequest(
            "{$client->getUsername()}/config/whitelist/{$this->username}",
            Phue_Transport_TransportInterface::METHOD_DELETE
        );
    }
}
