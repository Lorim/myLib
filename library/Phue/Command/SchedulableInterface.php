<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */


/**
 * Schedulable Interface
 */
interface Phue_Command_SchedulableInterface
{
    /**
     * Get schedulable request params
     *
     * @param Client $client Phue client
     *
     * @return array Key/value array of request params
     */
    public function getSchedulableParams(Phue_Client $client);
}
