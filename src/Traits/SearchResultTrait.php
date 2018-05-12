<?php
namespace Pbxg33k\InfoBase\Traits;

use Doctrine\Common\Collections\ArrayCollection;
use Pbxg33k\InfoBase\Model\ServiceResult;

trait SearchResultTrait
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
     * @return self
     * @deprecated use setResult
     */
    public function set(string $serviceKey, ServiceResult $result): self
    {
        return $this->setResult($serviceKey, $result);
    }

    /**
     * @param string $service
     * @param ServiceResult $result
     * @return self
     */
    public function setResult(string $service, ServiceResult $result): self
    {
        $this->results[$service] = $result;
        return $this;
    }

}