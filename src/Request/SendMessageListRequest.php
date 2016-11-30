<?php
namespace BrandChatApi\Request;

use BrandChatApi\Message;
use BrandChatApi\Request;

class SendMessageListRequest extends Request
{
    /**
     * @var string
     */
    protected $route = 'send/messages';

    /**
     * @var Message[]
     */
    private $messageList;

    /**
     * @param $messageList
     * @return SendMessageListRequest
     */
    public function setMessageList($messageList)
    {
        $this->messageList = $messageList;
        return $this;
    }

    /**
     * @return array
     */
    protected function getRequestConfig()
    {
        $payloadAsArray = array();
        foreach ($this->messageList as $message) {
            $payloadAsArray[] = $message->getData();
        }
        return $this->getRequestConfigForJsonPost($payloadAsArray);
    }
}