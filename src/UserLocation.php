<?php
namespace BrandChatApi;

/**
 * Class UserLocation
 * @package BrandChatApi
 * @method int getUserId()
 * @method float getLatitude()
 * @method float getLongitude()
 */
class UserLocation
{
    /**
     * @var array
     */
    private $data;

    public function __call($name, $args)
    {
        $direction = substr($name, 0, 3);
        $key = lcfirst(substr($name, 3));
        if ($direction === 'get') {
            return isset($this->data[$key]) ? $this->data[$key] : null;
        } elseif (substr($name, 0, 2)=='is') {
            return isset($this->data[$name]) ? $this->data[$name] : null;
        } else {
            throw new \Exception(__METHOD__ . " -- called unknown method '$name' with args = " . var_export($args, true));
        }
    }

    /**
     * @param $data
     * @return UserLocation
     */
    public static function fromArray($data)
    {
        $obj = new self;
        $obj->data = $data;
        return $obj;
    }
}