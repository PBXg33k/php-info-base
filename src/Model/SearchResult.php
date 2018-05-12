<?php
namespace Pbxg33k\InfoBase\Model;

use Doctrine\Common\Collections\ArrayCollection;

class SearchResult extends ArrayCollection
{
    /**
     * @param $service
     * @return ServiceResult
     */
    public function getServiceResult($service)
    {
        return $this->get($service);
    }

    /**
     * @param $service
     * @return ServiceResult
     *
     * @deprecated use getServiceResult
     */
    public function getResult($service)
    {
        return $this->getServiceResult($service);
    }

    /**
     * @return SearchResult
     */
    public function getSuccessfulResults()
    {
        return $this->filter(function($entry) {
            /** @var ServiceResult $entry */
            return $entry->isError() === false;
        });
    }

    /**
     * @param string $service
     * @param ServiceResult $result
     * @return self
     */
    public function setResult(string $service, ServiceResult $result): self
    {
        $this->set($service, $result);
        return $this;
    }
}