<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 14-12-16
 * Time: 上午9:37
 */

namespace MicroHome;

class Module{

    public function getConfig(){

        return include __DIR__ . '/config/module.config.php';
    }


    public function getAutoloaderConfig(){
        return array(
            'Zend\Loader\StandardAutoloader'=>array(
                'namespaces'=>array(
                    __NAMESPACE__ => __DIR__.'/src/'.__NAMESPACE__,
                )
            )
        );

    }

}