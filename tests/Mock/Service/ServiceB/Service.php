<?php
namespace Mock\Service\ServiceB;

use Pbxg33k\InfoBase\Model\IService;
use Pbxg33k\InfoBase\Service\BaseService;

class Service extends BaseService implements IService
{
    /**
     * {@inheritdoc}
     */
    public function init($config = null)
    {
        if (empty($config)) {
            $config = $this->getConfig();
        }

        $this->setInitialized(true);

        return $this;
    }
}