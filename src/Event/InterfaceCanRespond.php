<?php
namespace BrandChatApi\Event;

use BrandChatApi\Message;

interface InterfaceCanRespond
{
    /**
     * @param Message[] $messageList
     */
    public function respond($messageList);
}