<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 14-11-11
 * Time: 上午11:40
 */

namespace Exam;

use Zend\Loader\AutoloaderFactory;

class Module
{
    public function getConfig()
    {

        return include __DIR__.'/config/module.config.php';

    }

    public function getAutoloaderConfig()
    {

        return array(
            'Zend\Loader\StandardAutoloader'=>array(
                'namespaces' =>array(
                    __NAMESPACE__ => __DIR__.'/src/'.__NAMESPACE__,
                )
            )
        );

    }

}