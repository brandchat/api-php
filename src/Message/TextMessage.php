<?php
namespace BrandChatApi\Message;

use BrandChatApi\Message;

/**
 * Class Text
 * @package BrandChatApi\Message
 * @method string getText
 * @method $this setText($text)
 */
class TextMessage extends Message
{
    /**
     * @param string $label
     * @param string $value
     * @return $this
     */
    public function addPostBackButton($label, $value)
    {
        if (!isset($this->data['buttons'])) {
            $this->data['buttons'] = array();
        }
        $this->data['buttons'][] = array(
            'label' => $label,
            'value' => $value,
        );
        return $this;
    }

    /**
     * @param string $label
     * @param string $url
     * @return $this
     */
    public function addUrlButton($label, $url)
    {
        if (!isset($this->data['buttons'])) {
            $this->data['buttons'] = array();
        }
        $this->data['buttons'][] = array(
            'label' => $label,
            'url' => $url,
        );
        return $this;
    }
}