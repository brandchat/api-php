<?php
namespace BrandChatApi\Event;

use BrandChatApi\Event;
use BrandChatApi\EventException;
use BrandChatApi\Message;

class MessageEvent extends Event implements InterfaceCanRespond
{
    /**
     * @var \BrandChatApi\Message
     */
    private $message;

    public function __construct($data)
    {
        if (!isset($data['message'])) {
            throw new EventException('Message event data missing');
        }
        if (!isset($data['message']['type'])) {
            throw new EventException('Message type missing');
        }
        $this->data = $data;
        $this->message = Message::fromData($data['message']);
    }

    /**
     * @return Message
     */
    public function getMessage(): Message
    {
        return $this->message;
    }

    /**
     * @param Message[] $messageList
     */
    public function respond($messageList)
    {
        $this->renderMessageList($messageList);
    }
}