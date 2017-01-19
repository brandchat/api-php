BrandChat API Client PHP implementation
=======================================

## Requirements

* PHP >= 5.5
* [Guzzle](http://docs.guzzlephp.org/en/latest/)

## Help and docs

- [Documentation](https://github.com/brandchat/api-documentation)

## Installing

The recommended way to install the BrandChat API client is through
[Composer](http://getcomposer.org).

```bash
# Install Composer
curl -sS https://getcomposer.org/installer | php
```

Next, run the Composer command to install the latest stable version of the BrandChat API:

```bash
php composer.phar require brandchat/api-php
```

You can then later update the BrandChat API using composer:

```bash
composer.phar update
```

## Quickstart guide

### Configuring the framework

Before using the `BrandChatApi` framework, you need to initialise it with your bot's api key and bot identifier. Both of these can be found on the BrandChat dashboard menu for your bot after enabling the API for your bot.

```php
<?php
$apiKey = 'PUT_YOUR_API_KEY_HERE';
$botIdentifier = 'PUT_YOUR_BOT_IDENTIFIER_HERE';

$brandChat = \BrandChatApi\BrandChatApi::instance();
$brandChat->init($apiKey, $botIdentifier);
```

### Processing inbound events

Ensure that you've initialised the framework as above. Then, to handle an incoming event, register one or more handlers for different event types. In the following example, we'll handle inbound text messages, and send a reply to the user with "Hello world!" 

```php
<?php
$brandChat = \BrandChatApi\BrandChatApi::instance();

// create our text handler
$textHandler = function($event) {
    /** @var \BrandChatApi\Event\MessageEvent $event */
    /** @var \BrandChatApi\Message\TextMessage $textMessage */
    $textMessage = $event->getMessage();
    $text = $textMessage->getText(); // the message from the user
    $userId = $textMessage->getUserId(); // the user's unique ID

    // and respond with a message
    $responseMessage = new \BrandChatApi\Message\TextMessage();
    $responseMessage->setText('Hello world!')
                    ->setUserId($userId);

    $event->respond([$responseMessage]);
};

// register our handler for inbound text messages
$brandChat->onMessageText($textHandler);

// and process the inbound events
$brandChat->run();
```

Notes:

* You need to register at least one event handler, otherwise the framework will generate an exception.
* The relevant event handler will be called once you invoke the `run()` method on the `BrandChatApi` instance.
* If the event was of a type without a registered event handler, nothing will happen.
* The code sample above would typically be contained in your controller for the route which you register as the *callback URL* on the BrandChat API dashboard.
* As you can see in the snippet, it's possible to respond immediately to (subscribe and message) events with one or more messages back to the user.

### Sending a message

As before, please ensure that you have initialised the framework. Then, provided you have the user ID for a valid user (e.g. obtained from a previous inbound event), you can send a message to the user later.

```php
<?php
// create text message
$userId = 1337; // typically obtained from an event (like a message from a user)
$text = 'Hello world!'; // the message you want to send to the user

$textMessage = new \BrandChatApi\Message\TextMessage();
$textMessage->setUserId($userId)->setText($text);
$messageList[] = $textMessage;

// and send it!
$request = new \BrandChatApi\Request\SendMessageListRequest();
$response = $request->setMessageList([$textMessage])->execute();

if ($response->isSuccess()) {
    // Woohoo -- it was sent!
} else {
    // Hmmm, sending failed... Probably a bad key or bot identifier?
    $reason = $response->getReason(); // human readable reason for failure
}
```

In general, the preferred way to send messages to the user is by responding with one or more messages to a message event (as per *Processing inbound events* example above).

Sending messages asynchronously should only be done if:

* it will take several seconds or longer to respond to an inbound message event, or
* you want to send a message *to* a user later that isn't directly triggered by a message *from* a user.

Please note that different messaging platforms have different rules about asynchronous messages to users. WeChat, for example, only allows them in a 48 hour window since the user's last interaction with your bot. Facebook narrows that interaction window to 24 hours for most bots. 

### Web authentication

As always, please ensure that you have initialised the framework. Then, on the web route you've [configured for authenticated web access as per the documentation](https://github.com/brandchat/api-documentation/blob/master/web.md), get the one-time code that is passed through, and use it to retrieve the user's profile. The following example assumes that you've configured your URL to pass through the one-time code in the `code` parameter of the query string.

```php
<?php
$code = $_GET['code'];
$request = new \BrandChatApi\Request\OneTimeCodeLookupRequest();
$response = $request->setCode($code)->execute();

if ($response->isSuccess()) {
    // You can get various profile fields from the response, as follows:
    $displayName = $response->getUserProfile()->getDisplayName();
    $platformIdentifier = $response->getUserProfile()->getPlatformIdentifier();
    // todo: do something
    echo "<html><body><p>Hello, $displayName!</p></body></html>";
} else {
    die("Oops, we couldn't authenticate you :(");
}
```

Happy coding!
