<?php
namespace BrandChatApi\Request;

use BrandChatApi\BrandChatApi;
use BrandChatApi\Request;

class ProfileLookupRequest extends Request
{
    /**
     * @var string
     */
    protected $route = 'user/profile';

    /**
     * @var int
     */
    private $userId;

    /**
     * @param int $userId
     * @return ProfileLookupRequest
     */
    public function setUserId($userId)
    {
        $this->userId = (int)$userId;
        return $this;
    }

    /**
     * @return array
     */
    protected function getRequestConfig()
    {
        $payloadAsArray = array('userId' => $this->userId);
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