<?php
/**
 * Created by PhpStorm.
 * User:p flame
 * Date: 14-12-16
 * Time: 上午9:52
 */

namespace MicroHome\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\Sql\Expression;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;

/**
 * 审核表 的表关类
 *
 * Class ScrutinyTable
 * @package MicroHome\Model
 */
class ScrutinyTable extends AbstractTableGateway implements AdapterAwareInterface
{

    protected $table='shujuxia_microhome_scrutiny';

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

//    /**
//     * select all data
//     * @return \Zend\Db\ResultSet\ResultSet
//     */
//    public function getScrutiny(){
//        return $this->select();
//    }

    /**
     * select data by info_id
     * @param $InfoId
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getByInfoId($InfoId){
        return $this->select(array('info_id'=>$InfoId));
    }

    /**
     *
     * insert data
     * @param $data
     * @return int
     */
    public function createScrutiny($content,$info_id,$scrutiny_user){
        return $this->insert(array(
            'no_allow_content'=>$content,
            'info_id'=>$info_id,
            'create_at'=>new Expression('NOW()'),
            'scrutiny_user'=>$scrutiny_user
        ));
    }

    /**
     *
     * delete date by info_id
     * @param $id
     * @return int
     */
    public function deleteScrutinyByInfoId($info_id){
        return $this->delete(array('info_id'=>$info_id));
    }

    /**
     * update date by id
     * @param $data
     * @return int
     */
//    public function updateById($data){
//        return $this->update($data,array('id'=>$data['id']));
//    }
//
//    /**
//     *
//     * update date by info_id
//     * @param $data
//     * @return int
//     */
//    public function updateByInfoId($data){
//        return $this->update($data,array('info_id'=>$data['info_id']));
//    }

    /**
     * 过滤器
     *
     * @return InputFilter
     */
    public function getInputFilter()
    {
        $inputFilter = new InputFilter();
        $factory = new InputFactory();

        $inputFilter->add($factory->createInput(array(
            'name'     => 'info_id',
            'required' => true,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
                array('name' => 'Int'),
            ),
            'validators' => array(
                array('name' => 'NotEmpty'),
                array('name' => 'Digits'),
//                array(
//                    'name' => 'Zend\Validator\Db\RecordExists',
//                    'options' => array(
//                        'table' => 'shujuxia_micro_img',
//                        'field' => 'id',
//                        'adapter' => $this->adapter
//                    )
//                )
            ),
        )));

        return $inputFilter;
    }
}