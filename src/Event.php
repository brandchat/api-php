<?php
namespace BrandChatApi;

/**
 * Class Event
 * @package BrandChatApi
 * @method string getType()
 * @method int getTimestamp()
 */
abstract class Event
{
    /**
     * @var array
     */
    protected $data;

    /**
     * @param string $name
     * @param array $args
     * @return mixed|null
     * @throws \Exception
     */
    public function __call($name, $args)
    {
        $direction = substr($name, 0, 3);
        $key = lcfirst(substr($name, 3));
        if ($direction === 'get') {
            return isset($this->data[$key]) ? $this->data[$key] : null;
        }else {
            throw new \Exception(__METHOD__ . " -- called unknown method '$name' with args = " . var_export($args, true));
        }
    }

    /**
     * @return Event
     * @throws EventException
     * @throws SignatureException
     */
    public static function process()
    {
        $brandChat = BrandChatApi::instance();
        $bot = isset($_GET['bot']) ? $_GET['bot'] : null;
        if (!$bot) {
            throw new EventException('Bot identifier missing from request');
        }
        if ($bot !== $brandChat->getBotIdentifier()) {
            throw new EventException('Bot identifier mismatch');
        }
        $rawPayload = file_get_contents('php://input');
        $signature = isset($_SERVER['HTTP_X_CHAT_SIGNATURE']) ? $_SERVER['HTTP_X_CHAT_SIGNATURE'] : null;
        if (is_null($signature)) {
            throw new SignatureException('Signature missing');
        }
        $expectedSignature = $brandChat->calculateSignatureForPayload($rawPayload);
        if ($signature !== $expectedSignature) {
            throw new SignatureException('Signature mismatch');
        }
        $payload = json_decode($rawPayload, true);
        if (is_null($payload)) {
            throw new EventException('Payload could not be decoded');
        }
        if (!isset($payload['type'])) {
            throw new EventException('Type missing from request');
        }
        if (!isset($payload['timestamp'])) {
            throw new EventException('Timestamp missing from request');
        }

        $type = $payload['type'];
        $timestamp = $payload['timestamp'];
        if (!in_array($type, array('subscribe', 'unsubscribe', 'message', 'profile', 'location'))) {
            // unknown event type
            $type = 'unknown';
        }
        $eventClass = '\\BrandChatApi\\Event\\' . ucfirst($type) . 'Event';
        /** @var Event $event */
        $event = new $eventClass($payload);
        return $event;
    }

    /**
     * @param Message[] $messageList
     */
    protected function renderMessageList($messageList)
    {
        if ($messageList) {
            $data = array();
            foreach ($messageList as $message) {
                $data[] = $message->getData();
            }
            $json = json_encode($data);
            header('Content-Type: application/json');
            echo $json;
        }
    }
}

class SignatureException extends \Exception
{

}

class EventException extends \Exception
{

}