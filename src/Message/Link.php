<?php
namespace BrandChatApi\Message;

/**
 * Class Link
 * @package BrandChatApi\Message
 * @method $this setTitle(string $title)
 * @method $this setDescription(string $description)
 * @method $this setUrl(string $url)
 * @method $this setThumbnailUrl(string $url)
 */
class Link
{
    /**
     * @var array
     */
    private $data = array();

    public function __call($name, $args)
    {
        $direction = substr($name, 0, 3);
        $key = lcfirst(substr($name, 3));
        if ($direction === 'get') {
            return isset($this->data[$key]) ? $this->data[$key] : null;
        } elseif ($direction === 'set') {
            $value = $args[0];
            $this->data[$key] = $value;
            return $this;
        } else {
            throw new \Exception(__METHOD__ . " -- called unknown method '$name' with args = " . var_export($args, true));
        }
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}