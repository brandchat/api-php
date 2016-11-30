<?php
namespace BrandChatApi\Message;

use BrandChatApi\Message;

class ListMessage extends Message
{
    /**
     * @param Link $link
     * @return $this
     */
    public function addLink($link)
    {
        $this->data[] = $link->getData();
        return $this;
    }

    /**
     * @param Link[] $links
     * @return $this
     */
    public function setLinks($links)
    {
        $this->data = array(); // reset
        foreach ($links as $link) {
            $this->data[] = $link->getData();
        }
        return $this;
    }
}