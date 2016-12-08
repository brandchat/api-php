<?php
namespace BrandChatApi\Request;

use BrandChatApi\BrandChatApi;
use BrandChatApi\Request;

class OneTimeCodeLookupRequest extends Request
{
    /**
     * @var string
     */
    protected $route = 'code/claim';

    /**
     * @var string
     */
    private $code;

    /**
     * @param string $code
     * @return OneTimeCodeLookupRequest
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return array
     */
    protected function getRequestConfig()
    {
        $payloadAsArray = array('code' => $this->code);
        return $this->getRequestConfigForJsonPost($payloadAsArray);
    }

    /**
     * @return ProfileLookupResponse
     */
    public function execute()
    {
        $requestConfig = $this->getRequestConfig();
        $clientResponse = BrandChatApi::instance()->client()->post($this->route, $requestConfig);
        return new ProfileLookupResponse($clientResponse);
    }
}