<?php
namespace BrandChatApi;

use BrandChatApi\Event\MessageEvent;
use BrandChatApi\Message\MediaMessage;
use GuzzleHttp\Client;

/**
 * Class BrandChatApi
 * @package BrandChatApi
 * @method $this onLocation(callable $call)
 * @method $this onMessage(callable $call)
 * @method $this onMessageImage(callable $call)
 * @method $this onMessageMedia(callable $call)
 * @method $this onMessageText(callable $call)
 * @method $this onMessageVideo(callable $call)
 * @method $this onMessageVoice(callable $call)
 * @method $this onMessageUnknown(callable $call)
 * @method $this onProfile(callable $call)
 * @method $this onSubscribe(callable $function)
 * @method $this onUnknown(callable $function)
 * @method $this onUnsubscribe(callable $function)
 */
class BrandChatApi
{
    /**
     * @var array
     */
    private $config = array();

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $botIdentifier;

    /**
     * @var array
     */
    private $eventHandlers = array();

    private function __construct()
    {
        $this->config = require __DIR__ . DIRECTORY_SEPARATOR . 'Config.php';
    }

    /**
     * @param string $name
     * @param array $arguments
     * @return BrandChatApi
     * @throws \Exception
     */
    public function __call($name, $arguments)
    {
        static $eventTypes = array(
            'location', 'message', 'profile', 'subscribe', 'unknown', 'unsubscribe'
        );
        static $messageTypes = array(
            'image', 'media', 'text', 'unknown', 'video', 'voice'
        );
        if (strpos($name, 'on')===0) {
            $eventType = strtolower(substr($name, 2));
            $messageType = null;
            if (strpos($eventType, 'message')===0) {
                $messageType = substr($eventType, 7);
                $eventType = substr($eventType, 0, 7);
                if ($messageType && !in_array($messageType, $messageTypes)) {
                    throw new \Exception("$messageType is not a valid message type to register");
                } elseif (!$messageType) {
                    $messageType = null;
                }
            }
            if (!in_array($eventType, $eventTypes)) {
                throw new \Exception("$eventType is not a valid event type to register");
            }
            return $this->register($eventType, $arguments[0], $messageType);
        } else {
            throw new \Exception("$name is not a valid method");
        }
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @return string
     */
    public function getBotIdentifier(): string
    {
        return $this->botIdentifier;
    }

    /**
     * @param string $apiKey
     * @param string $botIdentifier
     * @param array $config
     */
    public function init($apiKey, $botIdentifier, $config = array())
    {
        $this->apiKey = $apiKey;
        $this->botIdentifier = $botIdentifier;
        if ($config) {
            $this->overrideConfig($config);
        }
    }

    /**
     * @param string $payload
     * @return string
     */
    public function calculateSignatureForPayload($payload)
    {
        return hash_hmac('sha1', $payload, $this->apiKey);
    }

    /**
     * @param string $filename
     * @return string
     */
    public function calculateSignatureForFile($filename)
    {
        return hash_hmac_file('sha1', $filename, $this->apiKey);
    }

    /**
     * @return Client
     */
    public function client()
    {
        static $client;
        if (is_null($client)) {
            $client = new Client($this->config);
        }
        return $client;
    }

    /**
     * @return Event
     */
    public function event()
    {
        return Event::process();
    }

    /**
     * @param string $eventType
     * @param callable $callable
     * @param string|null $messageType
     * @return $this
     */
    private function register($eventType, $callable, $messageType = null)
    {
        if ($messageType) {
            $this->eventHandlers[$eventType][$messageType] = $callable;
        } else {
            $this->eventHandlers[$eventType]['default'] = $callable;
        }
        return $this;
    }

    /**
     * Handles the incoming event. Requires event handlers to be set first. If nothing happens, then the event was
     * not of a registered type.
     */
    public function run()
    {
        if (!$this->eventHandlers) {
            throw new \Exception("Please set event handlers before invoking run()");
        }
        $event = $this->event();
        $type = $event->getType();
        if (array_key_exists($type, $this->eventHandlers)) {
            $subKey = 'default';
            if ($event instanceof MessageEvent) {
                $message = $event->getMessage();
                $messageType = $message->getType();
                if (array_key_exists($messageType, $this->eventHandlers[$type])) {
                    $subKey = $messageType;
                } elseif ($message instanceof MediaMessage && array_key_exists('media', $this->eventHandlers[$type])) {
                    $subKey = 'media';
                }
            }
            call_user_func($this->eventHandlers[$type][$subKey], $event);
        }
    }

    /**
     * @param array $config
     */
    private function overrideConfig($config)
    {
        $this->config = array_merge($this->config, $config);
    }

    /**
     * @return BrandChatApi
     */
    public static function instance()
    {
        static $instance;
        if (is_null($instance)) {
            $instance = new self;
        }
        return $instance;
    }
}