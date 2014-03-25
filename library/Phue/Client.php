<?php
/**
 * Phue: Philips Hue PHP Client
 *
 * @author    Michael Squires <sqmk@php.net>
 * @copyright Copyright (c) 2012-2014 Michael K. Squires
 * @license   http://github.com/sqmk/Phue/wiki/License
 */

/**
 * Client for connecting to Philips Hue bridge
 */
class Phue_Client
{
    /**
     * Client name
     */
    const DEFAULT_DEVICE_TYPE = 'Phue';

    /**
     * Host address
     *
     * @var string
     */
    protected $host = null;

    /**
     * Username
     *
     * @var string
     */
    protected $username = null;

    /**
     * Transport
     *
     * @var TransportInterface
     */
    protected $transport = null;

    /**
     * Construct a Phue Client
     *
     * @param string $host     Host address
     * @param string $username Username
     */
    public function __construct($host, $username = null)
    {
        $this->setHost($host);
        $this->setUsername($username);
    }

    /**
     * Get host
     *
     * @return string Host address
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Set host
     */
    public function setHost($host)
    {
        $this->host = (string) $host;
    }

    /**
     * Get username
     *
     * @return string Username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set username
     *
     * @param string $username Username
     */
    public function setUsername($username)
    {
        $this->username = (string) $username;
    }

    /**
     * Get bridge
     *
     * @return Bridge Bridge object
     */
    public function getBridge()
    {
        return $this->sendCommand(
            new Phue_Command_GetBridge()
        );
    }

    /**
     * Get users
     *
     * @return array List of User objects
     */
    public function getUsers()
    {
        return $this->sendCommand(
            new Phue_Command_GetUsers()
        );
    }

    /**
     * Get lights
     *
     * @return array List of Light objects
     */
    public function getLights()
    {
        return $this->sendCommand(
            new Phue_Command_GetLights()
        );
    }

    /**
     * Get light by id
     *
     * @return array List of Light objects
     */
    public function getLightById($id)
    {
        return $this->sendCommand(
            new Phue_Command_GetLightById($id)
        );
    }
    
    /**
     * Get groups
     *
     * @return array List of Group objects
     */
    public function getGroups()
    {
        return $this->sendCommand(
            new Phue_Command_GetGroups()
        );
    }

    /**
     * Get groups
     *
     * @return array List of Group objects
     */
    public function getGroupById($id)
    {
        return $this->sendCommand(
            new Phue_Command_GetGroupById($id)
        );
    }
    
    /**
     * Get schedules
     *
     * @return array List of Schedule objects
     */
    public function getSchedules()
    {
        return $this->sendCommand(
            new Phue_Command_GetSchedules()
        );
    }

    /**
     * Get transport
     *
     * @return TransportInterface
     */
    public function getTransport()
    {
        // Set transport if haven't
        if ($this->transport === null) {
            $this->transport = new Phue_Transport_Http($this);
        }

        return $this->transport;
    }

    /**
     * Set transport
     *
     * @param TransportInterface $transport Transport
     */
    public function setTransport(Hue_Transport_TransportInterface $transport)
    {
        $this->transport = $transport;
    }

    /**
     * Send command to server
     *
     * @param Command $command Phue command
     */
    public function sendCommand(Phue_Command_CommandInterface $command)
    {
        // Send command
        return $command->send($this);
    }
}
