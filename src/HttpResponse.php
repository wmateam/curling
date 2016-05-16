<?php
/**
 * User: wmateam
 * Date: 5/15/16
 * Time: 2:04 PM
 */

namespace wmateam\curling;


class HttpResponse
{
    private $result, $info;

    /**
     * Response constructor.
     * @param $result
     * @param $info
     */
    public function __construct($result, $info)
    {
        $this->result = $result;
        $this->info = $info;
    }

    public function getHeader()
    {
        $header_size = $this->getHeaderSize();
        return substr($this->result, 0, $header_size);
    }

    public function getBody()
    {
        return substr($this->result, $this->getHeaderSize());
    }

    public function getUrl()
    {
        return $this->info['url'];
    }

    public function getContentType()
    {
        return $this->info['content_type'];
    }

    public function getStatusCode()
    {
        return $this->info['http_code'];
    }

    public function getHeaderSize()
    {
        return $this->info['header_size'];
    }

    public function getRequestSize()
    {
        return $this->info['request_size'];
    }

    public function getFileTime()
    {
        return $this->info['filetime'];
    }

    public function getSslVerifyResult()
    {
        return $this->info['ssl_verify_result'];
    }

    public function getRedirectCount()
    {
        return $this->info['redirect_count'];
    }

    public function getRequestTime()
    {
        return $this->info['total_time'];
    }

    public function getSizeUpload()
    {
        return $this->info['size_upload'];
    }

    public function getSizeDownload()
    {
        return $this->info['size_download'];
    }

    public function getSpeedDownload()
    {
        return $this->info['speed_download'];
    }

    public function getSpeedUpload()
    {
        return $this->info['speed_upload'];
    }

    public function getRedirectUrl()
    {
        return $this->info['redirect_url'];
    }

}