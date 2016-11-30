<?php
namespace BrandChatApi\Request;

use BrandChatApi\BrandChatApi;
use BrandChatApi\Request;

class UploadFileRequest extends Request
{
    /**
     * @var string
     */
    protected $route = 'file/upload';

    /**
     * @var string
     */
    private $filename;

    /**
     * @param $filename
     * @return UploadFileRequest
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
        return $this;
    }

    /**
     * @return array
     */
    protected function getRequestConfig()
    {
        $signature = BrandChatApi::instance()->calculateSignatureForFile($this->filename);
        $payload = array(
            'query' => array('bot' => BrandChatApi::instance()->getBotIdentifier()),
            'headers' => array(
                'X-Chat-Signature' => $signature
            ),
            'multipart' => array(
                array(
                    'name' => 'media',
                    'filename' => pathinfo($this->filename, PATHINFO_BASENAME),
                    'contents' => fopen($this->filename, 'rb'),
                )
            )
        );
        return $payload;
    }

    /**
     * @return UploadFileResponse
     */
    public function execute()
    {
        $requestConfig = $this->getRequestConfig();
        $clientResponse = BrandChatApi::instance()->client()->post($this->route, $requestConfig);
        return new UploadFileResponse($clientResponse);
    }
}