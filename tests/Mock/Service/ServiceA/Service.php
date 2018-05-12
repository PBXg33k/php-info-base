<?php
namespace Mock\Service\ServiceA;

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

    public function search($argument)
    {
        // @codeCoverageIgnoreStart
        return $this->client->get('test', ['args']);
        // @codeCoverageIgnoreEnd
    }
}