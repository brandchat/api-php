<?php
namespace BrandChatApi;

abstract class Request
{
    /**
     * @var string
     */
    protected $route;

    /**
     * @return array
     */
    abstract protected function getRequestConfig();

    /**
     * @param array $payloadAsArray
     * @return array
     */
    protected function getRequestConfigForJsonPost($payloadAsArray)
    {
        $serialised = json_encode($payloadAsArray);
        $signature = BrandChatApi::instance()->calculateSignatureForPayload($serialised);
        $payload = array(
            'query' => array('bot' => BrandChatApi::instance()->getBotIdentifier()),
            'headers' => array(
                'Content-Type' => 'application/json',
                'X-Chat-Signature' => $signature
            ),
            'body' => $serialised
        );
        return $payload;
    }

    /**
     * @return Response
     */
    public function execute()
    {
        $requestConfig = $this->getRequestConfig();
        $clientResponse = BrandChatApi::instance()->client()->post($this->route, $requestConfig);
        return new Response($clientResponse);
    }
}