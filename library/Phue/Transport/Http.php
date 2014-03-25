<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */

/**
 * Http transport
 */
class Phue_Transport_Http implements Phue_Transport_TransportInterface
{
    /**
     * Phue Client
     *
     * @var Client
     */
    protected $client;

    /**
     * Adapter
     *
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * Exception map
     *
     * @var array
     */
    protected static $exceptionMap = [
        0   => 'Phue_Transport_Exception_BridgeException',
        1   => 'Phue_Transport_Exception_UnauthorizedUserException',
        2   => 'Phue_Transport_Exception_InvalidJsonBodyException',
        3   => 'Phue_Transport_Exception_ResourceUnavailableException',
        4   => 'Phue_Transport_Exception_MethodUnavailableException',
        5   => 'Phue_Transport_Exception_MissingParameterException',
        6   => 'Phue_Transport_Exception_ParameterUnavailableException',
        7   => 'Phue_Transport_Exception_InvalidValueException',
        8   => 'Phue_Transport_Exception_ParameterUnmodifiableException',
        101 => 'Phue_Transport_Exception_LinkButtonException',
        201 => 'Phue_Transport_Exception_DeviceParameterUnmodifiableException',
        301 => 'Phue_Transport_Exception_GroupTableFullException',
        302 => 'Phue_Transport_Exception_LightGroupTableFullException',
        901 => 'Phue_Transport_Exception_InternalErrorException',
    ];

    /**
     * Construct Http transport
     *
     * @param Client $client
     */
    public function __construct(Phue_Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get adapter for transport
     *
     * Auto created adapter if one is not present
     *
     * @return AdapterInterface Adapter
     */
    public function getAdapter()
    {
        if (!$this->adapter) {
            $this->setAdapter(new Phue_Transport_Adapter_Curl);
        }

        return $this->adapter;
    }

    /**
     * Set adapter
     *
     * @param AdapterInterface $adapter Transport adapter
     *
     * @return Http Self object
     */
    public function setAdapter(Phue_Transport_Adapter_AdapterInterface $adapter)
    {
        $this->adapter = $adapter;

        return $this;
    }

    /**
     * Send request
     *
     * @param string   $address API address
     * @param string   $method  Request method
     * @param stdClass $body    Post body
     *
     * @return string Request response
     */
    public function sendRequest($address, $method = self::METHOD_GET, \stdClass $body = null)
    {
        // Build request url
        $url = "http://{$this->client->getHost()}/api/{$address}";

        // Open connection
        $this->getAdapter()->open();

        // Send and get response
        $results     = $this->getAdapter()->send($url, $method, $body ? json_encode($body) : null);
        $status      = $this->getAdapter()->getHttpStatusCode();
        $contentType = $this->getAdapter()->getContentType();

        // Close connection
        $this->getAdapter()->close();

        // Throw connection exception if status code isn't 200 or wrong content type
        if ($status != 200 || $contentType != 'application/json') {
            throw new ConnectionException('Connection failure');
        }

        // Parse json results
        $jsonResults = json_decode($results);

        // Get first element in array if it's an array response
        if (is_array($jsonResults)) {
            $jsonResults = $jsonResults[0];
        }

        // Get error type
        if (isset($jsonResults->error)) {
            throw $this->getExceptionByType(
                $jsonResults->error->type,
                $jsonResults->error->description
            );
        }

        // Get success object only if available
        if (isset($jsonResults->success)) {
            $jsonResults = $jsonResults->success;
        }

        return $jsonResults;
    }

    /**
     * Get exception by type
     *
     * @param string $type        Error type
     * @param string $description Description of error
     *
     * @return Exception Built exception
     */
    public function getExceptionByType($type, $description)
    {
        // Determine exception
        $exceptionClass = isset(static::$exceptionMap[$type])
                        ? static::$exceptionMap[$type]
                        : static::$exceptionMap[0];

        return new $exceptionClass($description, $type);
    }
}
