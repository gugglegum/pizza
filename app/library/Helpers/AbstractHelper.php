<?php
/**
 * Pizza E96
 *
 * @author: Paul Melekhov
 */

namespace App\Helpers;

abstract class AbstractHelper
{
    /**
     * @var \App\HelperBroker
     */
    protected $_helperBroker;

    /**
     * @param \App\HelperBroker $helperBroker
     */
    public function __construct(\App\HelperBroker $helperBroker)
    {
        $this->_helperBroker = $helperBroker;
    }

    /**
     * @param string $name
     * @return mixed
     */
    protected function getResource($name)
    {
        return $this->_helperBroker->getBootstrap()->getResource($name);
    }

}
