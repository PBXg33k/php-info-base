<?php
use Pbxg33k\InfoBase\Model\SearchResult;

class SearchResultTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var SearchResult
     */
    protected $model;

    protected function setUp()
    {
        $this->model = new SearchResult([
            'fail' => (new \Pbxg33k\InfoBase\Model\ServiceResult())
                ->setError(true)
                ->setData((new \GuzzleHttp\Exception\RequestException(
                    'meh',
                    new \GuzzleHttp\Psr7\Request('GET', 'https://github.com')
                ))),
            'success' => (new \Pbxg33k\InfoBase\Model\ServiceResult())
                ->setError(false)
                ->setData('testData')
        ]);

        parent::setUp(); // TODO: Change the autogenerated stub
    }

    public function testGetResult()
    {
        $this->assertEquals(2, $this->model->count());
        $this->assertEquals(true, $this->model->getServiceResult('fail')->isError());
        $this->assertEquals(false, $this->model->getServiceResult('success')->isError());
    }

    public function testGetSuccessfulResults()
    {
        $filteredResult = $this->model->getSuccessfulResults();

        $this->assertEquals(1, $filteredResult->count());
        $this->assertEquals(false, $filteredResult->first()->isError());
        $this->assertEquals('testData', $filteredResult->first()->getData());
    }

    public function testSetResult()
    {
        $this->model->setResult('manual',
            (new \Pbxg33k\InfoBase\Model\ServiceResult())
                ->setError(false)
                ->setData('Manual data')
        );

        $this->assertEquals(3, $this->model->count());
        $this->assertEquals(false, $this->model->getServiceResult('manual')->isError());
        $this->assertEquals('Manual data', $this->model->getServiceResult('manual')->getData());
    }

    public function testDataSourceGetterSetter()
    {
        $dataSource = 'unittest';

        $this->assertEquals($dataSource, $this->model->first()->setDataSource($dataSource)->getDataSource());
    }

    public function testRawDataGetterSetter()
    {
        $rawData = ['meh' => 'Can actually be litterally anything'];

        $this->assertEquals($rawData, $this->model->first()->setRawData($rawData)->getRawData());
    }
}
