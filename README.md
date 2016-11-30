BrandChat API Client PHP implementation
=======================================

## Requirements

PHP >= 5.5
Guzzle

## Help and docs

- [Documentation](https://github.com/brandchat/api-documentation)

## Installing BrandChat API Cient

The recommended way to install BrandChat API is through
[Composer](http://getcomposer.org).

```bash
# Install Composer
curl -sS https://getcomposer.org/installer | php
```

Next, run the Composer command to install the latest stable version of the BrandChat API:

```bash
php composer.phar require brandchat/api-php
```

You can then later update BrandChat API using composer:

```bash
composer.phar update
```

## Quickstart

### Configuring the framework

Before using the BrandChatApi framework, you need to initialise it with your bot's api key and bot identifier. Both of these can be found on the BrandChat dashboard menu for your bot after enabling the API for your bot.

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

// create our text handler as a callable
$textHandler = function($event) {
    /** @var \BrandChatApi\Event\MessageEvent $event */
    /** @var \BrandChatApi\Message\TextMessage $textMessage */
    $textMessage = $event->getMessage();
    $messageText = $textMessage->getText(); // the text that the user sent in!
    $userId = $textMessage->getUserId(); // the user's unique ID

    // and respond with a message
    $responseMessage = new \BrandChatApi\Message\TextMessage();
    $responseMessage->setText('Hello world!')
                    ->setUserId($userId);

    $event->respond([$responseMessage]);
};

// register our handler
$brandChat->onMessageText($textHandler);

// and process the inbound events
$brandChat->run();
```

Notes:

* You need to register at least one event handler, otherwise the framework will generate an exception.
* The relevant event handler will be called once you invoke the `run()` method on the `BrandChatApi` instance.
* If the event was of a type without an event handler, nothing will happen.
* The code sample above would typically be contained in your controller for the route which you register as the *callback URL* on the BrandChat API dashboard.
* As you can see in the snippet, it's possible to respond immediately to (subscribe and message) events with one or more messages back to the user.

### Sending a message

As before, please ensure that you have initialised the framework. Then, provided you have the user ID for a valid user (e.g. obtained from a previous inbound event), you can send a message to the user later.

```php
<?php
// create text message
$userId = 1337; // typically obtained from an event (like a message from a user)
$message = 'Hello world!'; // your message!

$textMessage = new \BrandChatApi\Message\TextMessage();
$textMessage->setUserId($userId)->setText($message);
$messageList[] = $textMessage;

// and send it!
$request = new \BrandChatApi\Request\SendMessageListRequest();
$response = $request->setMessageList([$textMessage])->execute();

if ($response->isSuccess()) {
    // Woohoo!
} else {
    // hmmm... Probably a bad key or bot identifier?
}
```

Happy coding!
