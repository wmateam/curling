<?php
/**
 * User: wmateam
 * Date: 5/15/16
 * Time: 2:04 PM
 */

namespace wmateam\curling;


class CurlResponse
{
    private $result, $info, $isJson;

    /**
     * Response constructor.
     *
     * @param $result
     * @param $info
     */
    public function __construct($result, $info)
    {
        $this->result = $result;
        $this->info = $info;
        $this->isJson = preg_match('/(application\/json)/i', $this->getContentType());
    }

    /**
     * return response header
     *
     * @return string
     */
    public function getHeader()
    {
        $header_size = $this->getHeaderSize();
        return substr($this->result, 0, $header_size);
    }

    /**
     * retrieve response body
     *
     * @return string
     */
    public function getBody()
    {
        return substr($this->result, $this->getHeaderSize());
    }

    /**
     * return url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->info['url'];
    }

    /**
     * return response content type
     *
     * @return mixed
     */
    public function getContentType()
    {
        return $this->info['content_type'];
    }

    /**
     * return status code
     *
     * @return integer
     */
    public function getStatusCode()
    {
        return $this->info['http_code'];
    }

    /**
     * return header size
     *
     * @return integer
     */
    public function getHeaderSize()
    {
        return $this->info['header_size'];
    }

    /**
     * return request size
     *
     * @return integer
     */
    public function getRequestSize()
    {
        return $this->info['request_size'];
    }

    /**
     * return file time
     *
     * @return integer
     */
    public function getFileTime()
    {
        return $this->info['filetime'];
    }

    /**
     * return ssl verification result
     *
     * @return integer
     */
    public function getSslVerifyResult()
    {
        return $this->info['ssl_verify_result'];
    }

    /**
     * return redirection count
     *
     * @return integer
     */
    public function getRedirectCount()
    {
        return $this->info['redirect_count'];
    }

    /**
     * return request time
     * @return double
     */
    public function getRequestTime()
    {
        return $this->info['total_time'];
    }

    /**
     * return upload size
     * @return double
     */
    public function getSizeUpload()
    {
        return $this->info['size_upload'];
    }

    /**
     * return download size
     * @return double
     */
    public function getSizeDownload()
    {
        return $this->info['size_download'];
    }

    /**
     * return download speed
     * @return double
     */
    public function getSpeedDownload()
    {
        return $this->info['speed_download'];
    }

    /**
     * return upload speed
     * @return double
     */
    public function getSpeedUpload()
    {
        return $this->info['speed_upload'];
    }

    /**
     * return redirect url
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->info['redirect_url'];
    }

    /**
     * return response isJson ?
     * @return bool
     */
    public function isJson()
    {
        return $this->isJson > 0;
    }

    /**
     * return json Response
     * @param bool $asArray return as Array?
     * @return \stdClass|array
     */
    public function getJson($asArray = false)
    {
        if ($this->isJson())
            return json_decode($this->getBody(), $asArray);
        return null;
    }
}