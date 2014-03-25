<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */

/**
 * Adapter Interface
 */
interface Phue_Transport_Adapter_AdapterInterface
{
    /**
     * Opens the connection
     */
    public function open();

    /**
     * Sends request
     *
     * @param string $address Request path
     * @param string $method  Request method
     * @param string $data    Body data
     *
     * @return string Result
     */
    public function send($address, $method, $body = null);

    /**
     * Get http status code from response
     *
     * @return string Status code
     */
    public function getHttpStatusCode();

    /**
     * Get content type from response
     *
     * @return string Content type
     */
    public function getContentType();

    /**
     * Closes the connection
     */
    public function close();
}
