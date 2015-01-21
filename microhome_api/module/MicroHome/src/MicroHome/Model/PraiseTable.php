<?php
/**
 * Created by PhpStorm.
 * User: flame
 * Date: 14-12-17
 * Time: 下午2:45
 */

namespace MicroHome\Model;


use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;


/**
 * 点赞表的表关
 *
 * Class PraiseTable
 * @package MicroHome\Model
 */
class PraiseTable extends AbstractTableGateway implements AdapterAwareInterface
{
    protected $table = 'shujuxia_microhome_praise';

    /**
     * Set db adapter
     *
     * @param Adapter $adapter
     * @return AdapterAwareInterface
     */
    public function setDbAdapter(Adapter $adapter)
    {
        $this->adapter =$adapter;
        $this->initialize();
    }

    /**
     * @param $info_id
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getPraiseByInfoId($info_id)
    {
        return $this->select(array('info_id'=>$info_id,'flag'=>true));
    }

    /**
     * @param $info_id
     * @param $open_id
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getPraiseByInfoIdAndOpenId($info_id,$open_id)
    {
        return  $this->select(array('info_id'=>$info_id,'open_id'=>$open_id));

    }

    /**
     * @param $info_id
     * @param $open_id
     * @return int
     */
    public function updatePraise($info_id,$open_id,$status)
    {
        return $this->update(array('flag'=>$status),array('info_id'=>$info_id,'open_id'=>$open_id));
    }


    /**
     * @param $open_id
     * @param $info_id
     * @return int
     */
    public function createPraise($open_id,$info_id)
    {
        return $this->insert(array(
            'open_id'=>$open_id,
            'info_id'=>$info_id,
            'flag'=>1
        ));
    }

    /**
     * @param $info_id
     * @return int
     */
    public function deletePraiseByInfoId($info_id)
    {
        return $this->delete(array('info_id'=>$info_id));
    }

    /**
     * 过滤器，验证器
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
//                        'table' => 'shujuxia_microhome_praise',
//                        'field' => 'info_id',
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
            ),
            'validators' => array(
                array('name' => 'NotEmpty'),
//                array(
//                    'name' => 'Zend\Validator\Db\RecordExists',
//                    'options' => array(
//                        'table' => 'shujuxia_microhome_praise',
//                        'field' => 'open_id',
//                        'adapter' => $this->adapter
//                    )
//                )
            ),
        )));


        return $inputFilter;
    }
}