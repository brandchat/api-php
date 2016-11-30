<?php
namespace BrandChatApi\Request;

use BrandChatApi\Response;

class UploadFileResponse extends Response
{
    /**
     * @return int|null
     */
    public function getFileId()
    {
        $responseData = $this->deserialisedResponse();
        if (isset($responseData['fileId'])) {
            return $responseData['fileId'];
        } else {
            return null;
        }
    }
}