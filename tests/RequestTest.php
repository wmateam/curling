<?php

/**
 * User: wmateam
 * Date: 5/15/16
 * Time: 1:45 PM
 */

use wmateam\curling\CurlRequest;

class RequestTest extends PHPUnit_Framework_TestCase
{
    private $url = 'https://httpbin.org/';
    private $params = array('foo' => 'bar', 'baz' => 'boom', 'cow' => 'milk');
    private $headers = 'agent: wmateam\curling';

    public function testGetRequest()
    {
        $r = new CurlRequest($this->url. 'get');
        $r->setQueryString($this->params);
        $r->setHeader($this->headers);
        $data = $r->get();
        $this->assertEquals(200, $data->getStatusCode());
    }

    public function testPostRequest()
    {
        $r = new CurlRequest($this->url . 'post');
        $r->setQueryString($this->params);
        $r->setHeader($this->headers);
        $data = $r->post($this->params);
        $this->assertEquals(200, $data->getStatusCode());
    }

    public function testPutRequest()
    {
        $r = new CurlRequest($this->url . 'put');
        $r->setQueryString($this->params);
        $r->setHeader($this->headers);
        $data = $r->put($this->params);
        $this->assertEquals(200, $data->getStatusCode());
    }

    public function testPatchRequest()
    {
        $r = new CurlRequest($this->url . 'patch');
        $r->setQueryString($this->params);
        $r->setHeader($this->headers);
        $data = $r->patch($this->params);
        $this->assertEquals(200, $data->getStatusCode());
    }

    public function testDeleteRequest()
    {
        $r = new CurlRequest($this->url . 'delete');
        $r->setQueryString($this->params);
        $r->setHeader($this->headers);
        $data = $r->delete($this->params);
        $this->assertEquals(200, $data->getStatusCode());
    }

    public function testStatusCode()
    {
        $r = new CurlRequest($this->url . 'status/400');
        $r->setQueryString($this->params);
        $r->setHeader($this->headers);
        $data = $r->get();
        $this->assertEquals(400, $data->getStatusCode());
        $r = new CurlRequest($this->url . 'status/404');
        $r->setQueryString($this->params);
        $r->setHeader($this->headers);
        $data = $r->get();
        $this->assertEquals(404, $data->getStatusCode());
    }
}