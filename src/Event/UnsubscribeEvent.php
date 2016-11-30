<?php
namespace BrandChatApi\Event;

use BrandChatApi\Event;
use BrandChatApi\EventException;

class UnsubscribeEvent extends Event
{
    /**
     * @var int
     */
    private $userId;

    public function __construct($data)
    {
        if (!isset($data['unsubscribe']) || !isset($data['unsubscribe']['userId'])) {
            throw new EventException('Unsubscribe event data missing');
        }
        $this->data = $data;
        $this->userId = $data['unsubscribe']['userId'];
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }
}