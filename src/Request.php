<?php

/**
 * User: wmateam
 * Date: 5/14/16
 * Time: 1:59 PM
 */

namespace wmateam\curling;

class Request
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
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
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

    }

    /**
     * Set header params
     * @param array $header
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

    /**
     * @return Response
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
     * @return Response
     * @throws CurlingException
     */
    public function post($data = null, $type = self::FORM)
    {
        $this->optArray[CURLOPT_POST] = true;
        $this->optArray[CURLOPT_CUSTOMREQUEST] = "POST";
        $this->addParams($type, $data);
        return $this->result();
    }

    /**
     * @param array $data
     * @param int $type
     * @return Response
     * @throws CurlingException
     */
    public function put($data = null, $type = self::FORM)
    {
        $this->optArray[CURLOPT_CUSTOMREQUEST] = "PUT";
        $this->addParams($type, $data);
        return $this->result();
    }

    /**
     * @param array $data
     * @param int $type
     * @return Response
     * @throws CurlingException
     */
    public function patch($data = null, $type = self::FORM)
    {
        $this->optArray[CURLOPT_CUSTOMREQUEST] = "PATCH";
        $this->addParams($type, $data);
        return $this->result();
    }

    /**
     * @param array $data
     * @param int $type
     * @return Response
     * @throws CurlingException
     */
    public function delete($data = null, $type = self::FORM)
    {
        $this->optArray[CURLOPT_CUSTOMREQUEST] = "DELETE";
        $this->addParams($type, $data);
        return $this->result();
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
     * @throws CurlingException
     */
    private function addParams($type, $data)
    {
        $isString = false;
        if ($type == self::RAW_DATA)
            $isString = true;


        if ($type == self::X_WWW_FROM_URLENCODED)
            array_push($this->optHeader, 'content-type: application/x-www-form-urlencoded');

        if ($type == self::RAW_DATA)
            array_push($this->optHeader, 'content-type: text/plain');
        $this->optArray[CURLOPT_HTTPHEADER] = $this->optHeader;
        $this->validateFields($data, $isString);
        $this->optArray[CURLOPT_POSTFIELDS] = $data;
    }

    /**
     * @return Response
     * @throws CurlingException
     */
    private function result()
    {
        try {
            if ($this->queryString != null)
                $this->optArray[CURLOPT_URL] = $this->optArray[CURLOPT_URL] . '?' . $this->queryString;
            curl_setopt_array($this->channel, $this->optArray);
            return new Response(curl_exec($this->channel), curl_getinfo($this->channel));
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