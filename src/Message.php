<?php
namespace BrandChatApi;
/**
 * Class Message
 * @package BrandChatApi
 * @method $this setUserId($userId)
 * @method $this setType($type)
 * @method int getUserId()
 * @method string getType()
 */
abstract class Message
{
    /**
     * @var array
     */
    protected $data = array();

    public function __construct()
    {
        $class = get_class($this);
        if ($pos = strrpos($class, '\\')) {
            // remove namespace
            $class = substr($class, $pos + 1);
            // remove "message"
            if (($pos = strpos($class, "Message")) !== false) {
                $class = substr($class, 0, $pos);
            }
        }
        $this->data['type'] = strtolower($class);
    }

    public function __call($name, $args)
    {
        $direction = substr($name, 0, 3);
        $key = lcfirst(substr($name, 3));
        if ($direction === 'get') {
            return $this->get($key);
        } elseif ($direction === 'set') {
            $value = $args[0];
            return $this->set($key, $value);
        } else {
            throw new \Exception(__METHOD__ . " -- called unknown method '$name' with args = " . var_export($args, true));
        }
    }

    /**
     * Constructs a message object based on the data (to be used for user-originated messages only)
     *
     * @param string $data
     * @return Message
     */
    public static function fromData($data)
    {
        $type = $data['type'];
        if (!in_array($type, array('image', 'text', 'video', 'voice'))) {
            $type = 'unknown';
        }
        $class = '\\BrandChatApi\\Message\\' . ucfirst($type) . 'Message';
        /** @var Message $message */
        $message = new $class;
        $message->data = $data;
        return $message;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    private function get($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    private function set($key, $value)
    {
        $this->data[$key] = $value;
        return $this;
    }
}