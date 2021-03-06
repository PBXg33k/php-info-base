<?php
/**
 * Created by PhpStorm.
 * User: PBX_g33k
 * Date: 12/05/2018
 * Time: 14:05
 */

use Pbxg33k\InfoBase\Model\RequestError;

class RequestErrorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var RequestError
     */
    protected $model;

    protected function setUp()
    {
        $this->model = new RequestError();
        parent::setUp(); // TODO: Change the autogenerated stub
    }

    public function testResponse()
    {
        $response = $this->createMock(\Psr\Http\Message\ResponseInterface::class);

        $this->assertEquals($response, $this->model->setResponse($response)->getResponse());
    }

    public function testRequest()
    {
        $request = $this->createMock(\Psr\Http\Message\RequestInterface::class);

        $this->assertEquals($request, $this->model->setRequest($request)->getRequest());
    }

    public function testException()
    {
        $exception = $this->createMock(\Exception::class);

        $this->assertEquals($exception, $this->model->setException($exception)->getException());
    }

    public function testArgument()
    {
        $arg = 'meh';

        $this->assertEquals($arg, $this->model->setArguments($arg)->getArguments());
    }

    public function testService()
    {
        $service = 'ANYTHING';

        $this->assertEquals($service, $this->model->setService($service)->getService());
    }
}
