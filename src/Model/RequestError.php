<?php
namespace Pbxg33k\InfoBase\Model;

use Guzzle\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class RequestError
{
    /**
     * @var string
     */
    private $service;

    /**
     * @var string
     */
    private $argument;

    /**
     * @var \Exception
     */
    private $exception;

    /**
     * @var RequestInterface|\Psr\Http\Message\RequestInterface
     */
    private $request;

    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * @return string
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param string $service
     * @return RequestError
     */
    public function setService($service)
    {
        $this->service = $service;
        return $this;
    }

    /**
     * @return string
     */
    public function getArgument()
    {
        return $this->argument;
    }

    /**
     * @param string $arguments
     * @return RequestError
     */
    public function setArguments($arguments)
    {
        $this->arguments = $arguments;
        return $this;
    }

    /**
     * @return \Exception
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * @param \Exception $exception
     * @return RequestError
     */
    public function setException($exception)
    {
        $this->exception = $exception;
        return $this;
    }

    /**
     * @return RequestInterface|\Psr\Http\Message\RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param RequestInterface|\Psr\Http\Message\RequestInterface $request
     * @return RequestError
     */
    public function setRequest($request)
    {
        $this->request = $request;
        return $this;
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param ResponseInterface $response
     * @return RequestError
     */
    public function setResponse($response)
    {
        $this->response = $response;
        return $this;
    }


}