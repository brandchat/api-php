<?php
namespace BrandChatApi\Event;

use BrandChatApi\Event;
use BrandChatApi\EventException;
use BrandChatApi\Message;

class SubscribeEvent extends Event implements InterfaceCanRespond
{
    /**
     * @var int
     */
    private $userId;

    public function __construct($data)
    {
        if (!isset($data['subscribe']) || !isset($data['subscribe']['userId'])) {
            throw new EventException('Subscribe event data missing');
        }
        $this->data = $data;
        $this->userId = $data['subscribe']['userId'];
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param Message[] $messageList
     */
    public function respond($messageList)
    {
        $this->renderMessageList($messageList);
    }
}