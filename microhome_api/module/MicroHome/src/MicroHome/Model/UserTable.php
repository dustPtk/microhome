<?php
/**
 * Created by PhpStorm.
 * User:  flame
 * Date: 14-12-16
 * Time: 上午9:51
 */

namespace MicroHome\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
/**
 * 用户表关类
 *
 * Class UserTable
 * @package MicroHome\Model
 */
class UserTable extends AbstractTableGateway implements AdapterAwareInterface{
    protected $table='shujuxia_microhome_user';
    /**
     * Set db adapter
     *
     * @param Adapter $adapter
     * @return AdapterAwareInterface
     */
    public function setDbAdapter(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->initialize();
    }

    /**
     * insert data
     *
     * @param $data
     * @return mixed
     */
    public function createUser($open_id,$username,$headurl){
        return $this->insert(array(
            'open_id'=>$open_id,
            'username'=>$username,
            'app_id'=>null,
            'headurl'=>$headurl,
        ));
    }

    /**
     * select all data
     *
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getUser(){
        return $this->select();
    }

    /**
     * select data by open_id
     * @param $openid
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getUserByOpenId($open_id){
        $rowset =  $this->select(array('open_id'=>$open_id));
        return $rowset->current();
    }

    /**
     * delete data by id
     *
     * @param $id
     * @return int
     */
    public function deleteUserById($id){
        return $this->delete(array('id'=>$id));

    }

    /**
     * delete data by openid
     *
     * @param $openid
     * @return int
     */
    public function deleteUserByOpenId($openid){
        return $this->delete(array('open_id'=>$openid));

    }


    //过滤器

    public function getInputFilter()
    {
        $inputFilter = new InputFilter();
        $factory = new InputFactory();

        $inputFilter->add($factory->createInput(array(
            'name'     => 'username',
            'required' => true,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
//                array('name' => 'Int'),
            ),
            'validators' => array(
                array('name' => 'NotEmpty'),
//                array('name' => 'Digits'),
                array(
                    'name' => 'Zend\Validator\Db\RecordExists',
                    'options' => array(
                        'table' => $this->table,
                        'field' => 'id',
                        'adapter' => $this->adapter
                    )
                )
            ),
        )));

        $inputFilter->add($factory->createInput(array(
            'name'     => 'headurl',
            'required' => true,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
//                array('name' => 'Int'),
            ),
            'validators' => array(
                array('name' => 'NotEmpty'),
//                array('name' => 'Digits'),
//                array(
//                    'name' => 'Zend\Validator\Db\RecordExists',
//                    'options' => array(
//                        'table' => $this->table,
//                        'field' => 'id',
//                        'adapter' => $this->adapter
//                    )
//                )
            ),
        )));

        $inputFilter->add($factory->createInput(array(
            'name'     => 'open_id',
            'required' => true,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
//                array('name' => 'Int'),
            ),
            'validators' => array(
                array('name' => 'NotEmpty'),
//                array('name' => 'Digits'),
//                array(
//                    'name' => 'Zend\Validator\Db\RecordExists',
//                    'options' => array(
//                        'table' => $this->table,
//                        'field' => 'id',
//                        'adapter' => $this->adapter
//                    )
//                )
            ),
        )));

        return $inputFilter;
    }

}