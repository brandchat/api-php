<?php
namespace BrandChatApi\Event;

use BrandChatApi\Event;
use BrandChatApi\EventException;
use BrandChatApi\UserLocation;

class LocationEvent extends Event
{
    /**
     * @var UserLocation
     */
    private $userLocation;

    public function __construct($data)
    {
        if (!isset($data['location']) || !isset($data['location']['userId'])) {
            throw new EventException('Location event data missing');
        }
        $this->data = $data;
        $this->userLocation = UserLocation::fromArray($data['location']);
    }

    /**
     * @return UserLocation
     */
    public function getUserLocation(): UserLocation
    {
        return $this->userLocation;
    }
}