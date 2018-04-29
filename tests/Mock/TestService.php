<?php
namespace Mock;

use Pbxg33k\InfoBase\InfoService;

class TestService extends InfoService
{

    /**
     * A temp method which should return the namespace.
     * Ideally this should be automagically figured out.
     *
     * @return string
     */
    protected function getNamespace()
    {
        return __NAMESPACE__;
    }
}