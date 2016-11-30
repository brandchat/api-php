<?php
namespace BrandChatApi;

use Psr\Http\Message\ResponseInterface;

class Response
{
    /**
     * @var ResponseInterface
     */
    protected $response;

    /**
     * @param ResponseInterface $clientResponse
     */
    public function __construct($clientResponse)
    {
        $this->response = $clientResponse;
    }

    /**
     * @return array
     */
    protected function deserialisedResponse()
    {
        static $responseData;
        if (is_null($responseData)) {
            $responseData = json_decode($this->response->getBody(), true);
            if (is_null($responseData)) {
                $responseData = array();
            }
        }
        return $responseData;
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return $this->response->getStatusCode()==200;
    }

    public function getStatusCode()
    {
        return $this->response->getStatusCode();
    }

    /**
     * @return string
     */
    public function getReason()
    {
        $responseData = $this->deserialisedResponse();
        if (isset($responseData['reason'])) {
            return $responseData['reason'];
        } else {
            return trim("{$this->getStatusCode()} {$this->response->getReasonPhrase()}");
        }
    }
}