<?php
namespace Pbxg33k\InfoBase\Model;

use Doctrine\Common\Collections\ArrayCollection;

class SearchResult
{
    /**
     * @var ArrayCollection[ServiceResult]
     *
     * List of results.
     * IE:
     *      ArrayCollection [
     *          'servicea' => ServiceResult,
     *          'serviceb' => ServiceResult
     *      ]
     */
    protected $results;

    /**
     * @param $service
     * @return ServiceResult
     */
    public function getResult($service)
    {
        return $this->results[$service];
    }

    /**
     * @return ArrayCollection
     */
    public function getAllResults()
    {
        return $this->results;
    }

    public function getSuccessfulResults()
    {
        return $this->results->filter(function($entry) {
            /** @var ServiceResult $entry */
            return $entry->isError() === false;
        });
    }

    /**
     * Backwards compatibility
     *
     * @param string $serviceKey
     * @param ServiceResult $result
     * @return SearchResult
     * @deprecated use setResult
     */
    public function set(string $serviceKey, ServiceResult $result): SearchResult
    {
        return $this->setResult($serviceKey, $result);
    }

    /**
     * @param string $service
     * @param ServiceResult $result
     * @return SearchResult
     */
    public function setResult(string $service, ServiceResult $result): SearchResult
    {
        $this->results[$service] = $result;
        return $this;
    }

}