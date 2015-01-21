<?php
/**
 * Created by PhpStorm.
 * User: flame
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
 * 图片表的表关
 *
 * Class ImgTable
 * @package MicroHome\Model
 */
class ImgTable extends AbstractTableGateway implements AdapterAwareInterface{

    protected $table='shujuxia_microhome_img';

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
     * select data by info_id
     *
     * @param $info_id
     */
    public function getImgByInfoId($info_id){
       return $this->select(array('info_id'=>$info_id));
    }


    /**
     * insert data
     *
     * @param $data
     * @return int
     *
     */
    public function createImg($img_max_url,$info_id){
        return $this->insert(array(
            'img_max_url' => $img_max_url,
            'img_min_url' => $img_max_url,
            'info_id' => $info_id
        ));
    }

    /**
     * delete data by info_id
     *
     * @param $info_id
     * @return int
     */
    public function deleteImgByInfoId($info_id){
        return $this->delete(array('info_id'=>$info_id));
    }

    /**
     * @return InputFilter
     */
//    public function getInputFilter()
//    {
//        $inputFilter = new InputFilter();
//        $factory = new InputFactory();
//
//        $inputFilter->add($factory->createInput(array(
//            'name'     => 'info_id',
//            'required' => true,
//            'filters'  => array(
//                array('name' => 'StripTags'),
//                array('name' => 'StringTrim'),
//                array('name' => 'Int'),
//            ),
//            'validators' => array(
//                array('name' => 'NotEmpty'),
//                array('name' => 'Digits'),
//                array(
//                    'name' => 'Zend\Validator\Db\RecordExists',
//                    'options' => array(
//                        'table' => 'shujuxia_micro_img',
//                        'field' => 'id',
//                        'adapter' => $this->adapter
//                    )
//                )
//            ),
//        )));
//
//        return $inputFilter;
//    }
}