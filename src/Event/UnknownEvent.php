<?php
namespace BrandChatApi\Event;

use BrandChatApi\Event;

class UnknownEvent extends Event
{
    public function __construct($data)
    {
        $this->data = $data;
    }
}