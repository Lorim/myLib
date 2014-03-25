<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */

/**
 * Command Interface
 */
interface Phue_Command_CommandInterface
{
    /**
     * Send command
     *
     * @param Client $client Phue Client
     *
     * @return mixed
     */
    public function send(Phue_Client $client);
}
