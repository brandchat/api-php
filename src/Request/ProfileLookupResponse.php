<?php
namespace BrandChatApi\Request;

use BrandChatApi\Response;
use BrandChatApi\UserProfile;

class ProfileLookupResponse extends Response
{
    /**
     * @return UserProfile|null
     */
    public function getUserProfile()
    {
        $responseData = $this->deserialisedResponse();
        if (isset($responseData['userId'])) {
            return UserProfile::fromArray($responseData);
        } else {
            return null;
        }
    }
}