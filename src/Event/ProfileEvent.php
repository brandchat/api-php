<?php
namespace BrandChatApi\Event;

use BrandChatApi\Event;
use BrandChatApi\EventException;
use BrandChatApi\UserProfile;

class ProfileEvent extends Event
{
    /**
     * @var UserProfile
     */
    private $userProfile;

    public function __construct($data)
    {
        if (!isset($data['profile']) || !isset($data['profile']['userId'])) {
            throw new EventException('Profile event data missing');
        }
        $this->data = $data;
        $this->userProfile = UserProfile::fromArray($data['profile']);
    }

    /**
     * @return UserProfile
     */
    public function getUserProfile(): UserProfile
    {
        return $this->userProfile;
    }
}