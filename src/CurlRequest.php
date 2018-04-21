<?php

/**
 * User: wmateam
 * Date: 5/14/16
 * Time: 1:59 PM
 */

namespace wmateam\curling;

class CurlRequest
{
    private $channel = null;
    private $optArray = array();
    private $optHeader = array();
    private $queryString = null;

    const X_WWW_FROM_URLENCODED = 0;
    const FORM = 1;
    const RAW_DATA = 2;

    /**
     * Request constructor.
     * @param $url
     * @throws CurlingException
     */
    public function __construct($url)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL) === false) {

        } else {
            throw new CurlingException('$url');
        }
        $this->channel = curl_init();
        $this->optArray[CURLOPT_URL] = $url;
        $this->optArray[CURLOPT_RETURNTRANSFER] = true;
        $this->optArray[CURLOPT_VERBOSE] = false;
        $this->optArray[CURLOPT_HEADER] = true;
        $this->optArray[CURLOPT_FOLLOWLOCATION] = true;
        $this->optArray[CURLOPT_ENCODING] = '';
        $this->optArray[CURLOPT_MAXREDIRS] = 10;
        $this->optArray[CURLOPT_TIMEOUT] = 30;
        $this->optArray[CURLOPT_HTTP_VERSION] = CURL_HTTP_VERSION_1_1;
        $this->optArray[CURLOPT_USERAGENT] = 'wmateam-curling/0.7.5';
    }

    /**
     * @return CurlResponse
     * @throws CurlingException
     */
    public function get()
    {
        $this->optArray[CURLOPT_CUSTOMREQUEST] = "GET";
        return $this->result();
    }

    /**
     * @param array $data
     * @param int $type
     * @param bool $isJSON
     * @return CurlResponse
     * @throws CurlingException
     */
    public function post($data = null, $type = self::FORM, $isJSON = false)
    {
        $this->optArray[CURLOPT_POST] = true;
        $this->optArray[CURLOPT_CUSTOMREQUEST] = "POST";
        $this->addParams($type, $data, $isJSON);
        return $this->result();
    }

    /**
     * @param array $data
     * @param int $type
     * @param bool $isJSON
     * @return CurlResponse
     * @throws CurlingException
     */
    public function put($data = null, $type = self::FORM, $isJSON = false)
    {
        $this->optArray[CURLOPT_CUSTOMREQUEST] = "PUT";
        $this->addParams($type, $data, $isJSON);
        return $this->result();
    }

    /**
     * @param array $data
     * @param int $type
     * @param bool $isJSON
     * @return CurlResponse
     * @throws CurlingException
     */
    public function patch($data = null, $type = self::FORM, $isJSON = false)
    {
        $this->optArray[CURLOPT_CUSTOMREQUEST] = "PATCH";
        $this->addParams($type, $data, $isJSON);
        return $this->result();
    }

    /**
     * @param array $data
     * @param int $type
     * @param bool $isJSON
     * @return CurlResponse
     * @throws CurlingException
     */
    public function delete($data = null, $type = self::FORM, $isJSON = false)
    {
        $this->optArray[CURLOPT_CUSTOMREQUEST] = "DELETE";
        $this->addParams($type, $data, $isJSON);
        return $this->result();
    }

    public function setAuthentication($username, $password)
    {
        $this->optArray[CURLOPT_USERPWD] = $username . ':' . $password;
        $this->optArray[CURLOPT_UNRESTRICTED_AUTH] = true;
    }


    public function setProxy($proxy, $username = null, $password = null)
    {
        if ($proxy === '') {
            throw new CurlingException('$proxy', CurlingException::INVALID_TYPE);
        }

        $this->optArray[CURLOPT_PROXY] = $proxy;
        $this->optArray[CURLOPT_PROXYUSERPWD] = $username . ':' . $password;
    }

    /**
     * Set header params
     * @param string $header
     */
    public function setHeader($header)
    {
        array_push($this->optHeader, $header);
    }

    /**
     * @param array $data
     * @throws CurlingException
     */
    public function setQueryString($data = null)
    {
        $this->validateFields($data);
        $this->queryString = http_build_query($data);
    }

    public function setUserAgent($agent)
    {
        if (!is_string($agent))
            throw new CurlingException('(string)$agent', CurlingException::INVALID_TYPE);
        $this->userAgent($agent);
    }

    private function userAgent($agent)
    {
        $this->optArray[CURLOPT_USERAGENT] = $agent;
    }

    /**
     * @param $data
     * @param bool $isString
     * @throws CurlingException
     */
    private function validateFields($data, $isString = false)
    {
        if ($data != null) {
            if ($isString) {
                if (!is_string($data)) {
                    throw new CurlingException('(string)$postData', CurlingException::INVALID_TYPE);
                }
                return;
            }
            if (!is_array($data)) {
                throw new CurlingException('(array)$postData', CurlingException::INVALID_TYPE);
            }
        }
    }

    /**
     * @param $type
     * @param $data
     * @param bool $isJSON
     * @throws CurlingException
     */
    private function addParams($type, $data, $isJSON = false)
    {
        $isString = false;


        if ($type == self::X_WWW_FROM_URLENCODED) {
            $this->setHeader('content-type: application/x-www-form-urlencoded');
            $data = http_build_query($data);
            $isString = true;
        }

        if ($type == self::RAW_DATA) {
            $isString = true;
            if ($isJSON)
                $this->setHeader('content-type: application/json');
            else
                $this->setHeader('content-type: text/plain');
        }

        $this->validateFields($data, $isString);
        $this->optArray[CURLOPT_POSTFIELDS] = $data;
    }

    /**
     * @return CurlResponse
     * @throws CurlingException
     */
    private function result()
    {
        try {
            if ($this->queryString != null)
                $this->optArray[CURLOPT_URL] = $this->optArray[CURLOPT_URL] . '?' . $this->queryString;
            $this->optArray[CURLOPT_HTTPHEADER] = $this->optHeader;
            curl_setopt_array($this->channel, $this->optArray);
            return new CurlResponse(curl_exec($this->channel), curl_getinfo($this->channel));
        } catch (\Exception $e) {
            throw new CurlingException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * @return string
     */
    public function getErrors()
    {
        return curl_error($this->channel);
    }

    function __destruct()
    {
        curl_close($this->channel);
    }


}