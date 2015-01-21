<?php
/**
 * Created by PhpStorm.
 * User:    flame
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
 * 话题表的表关
 *
 * Class InfoTable
 * @package MicroHome\Model
 */
class InfoTable extends AbstractTableGateway implements AdapterAwareInterface{
    protected $table='shujuxia_microhome_info';

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
     * select all data
     *
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getInfoShow(){
        $select = $this->sql->select()->where("info_status=0 or info_status=1")->order('create_at DESC')->limit(10);
//        return  $this->select(array('info_status'=>1));
//        ->toArray();
//        print_r($temp);
        return $this->selectWith($select);
    }

    public function getInfoAll()
    {
        return $this->select();
    }

    /**
     * select data by id
     *
     * @param $id
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getInfoById($id){
        $rowset =  $this->select(array('id'=>$id));
        return $rowset->current();
    }

    public function getInfoByIdAndNum($id,$num)
    {
        $select = $this->sql->select();
        $where = $select->where;
        $where->lessThan('id',(int)$id);
        $select->order('create_at DESC');
        $select->limit((int)$num);
        return $this->selectWith($select);
    }
    /**
     * select data by open_id
     *
     * @param $openid
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getInfoByOpenIdAndInfoIdANdNum($open_id,$id,$num){
        $select = $this->sql->select();
        $where = $select->where;
        $where->equalTo('open_id',$open_id);
        $where->lessThan('id',(int)$id);
        $select->order('create_at DESC');
        $select->limit((int)$num);
        return $this->selectWith($select);
    }


    public function getInfoByOpenIdAndNum($open_id,$num){
        $select = $this->sql->select();
        $select->where(array('open_id'=>$open_id));
        $select->order('create_at DESC');
        $select->limit((int)$num);
        return $this->selectWith($select);
    }


    /**
     * select data by info_status
     *
     * @param $infostatus
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getInfoByInfoStatus($info_status){
        $select =$this->sql->select()->where(array('info_status'=>$info_status))->limit(1);
        return  $this->selectWith($select);
    }


    /**
     * select data by open_id and info_status
     *
     * @param $openid
     * @param $info_status
     * @return \Zend\Db\ResultSet\ResultSet
     *
     */
    public function getInfoByOpenIdAndInfoStatus($open_id,$info_status){
        return $this->select(array('open_id'=>$open_id,'info_status'=>$info_status));

    }

    public function getInfoByOpenIdAndCrateAt($open_id,$create_at)
    {
        $rowset = $this->select(array('open_id'=>$open_id,'create_at'=>$create_at));
        return $rowset->current();
    }

    /**
     * insert data
     *
     * @param $create_at
     * @param $content
     * @param $number
     * @param $open_id
     * @return int
     */
    public function createInfo($create_at,$content,$number,$open_id){
        return $this->insert(array(
            'create_at'=>$create_at,
            'praise'=>0,
            'comment_num'=>0,
            'content'=>$content,
            'number'=>$number,
            'info_status'=>0,
            'open_id'=>$open_id
        ));
    }

    /**
     * delete data by id
     *
     * @param $id
     */
    public function deleteInfoById($id){
        return $this->update(array('info_status'=>3),array('id'=>$id));
    }


    /**
     * delete data by open_id
     *
     * @param $openid
     * @return int
     */
//    public function deleteInfoByOpenId($open_id){
//        return $this->delete(array('open_id'=>$open_id));
//    }


    /**
     * truncate all date
     *
     * @return int
     *
     */
//    public function deleteInfo(){
//        return $this->delete(array(1));
//    }


    /**
     * update data by id
     *
     * @param $id
     * @param $data
     * @return int
     *
     */
    public function updateInfoById($id,$info_status){
        $res = $this->update(array('info_status'=>$info_status),array('id'=>$id));
        return $res;
    }

    /**
     * @param $id
     * @param $praise
     * @return int
     */
    public function updatePraiseById($id,$praise){
        return $this->update(array('praise'=>$praise),array('id'=>$id));
    }

    /**
     * @param $id
     * @param $commnetnum
     * @return int
     */
    public function updateCommentNumById($id,$commnetnum){
        return $this->update(array('comment_num'=>$commnetnum),array('id'=>$id));
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
            'name'     => 'content',
            'required' => true,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array('name' => 'NotEmpty'),
//                array('name' => 'Digits'),
//                array(
//                    'name' => 'Zend\Validator\Db\RecordExists',
//                    'options' => array(
//                        'table' => 'shujuxia_microhome_info',
//                        'field' => 'id',
//                        'adapter' => $this->adapter
//                    )
//                )
            ),
        )));

        $inputFilter->add($factory->createInput(array(
            'name'     => 'number',
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
//                        'table' => 'shujuxia_microhome_info',
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
                array('name' => 'Int'),
            ),
            'validators' => array(
                array('name' => 'NotEmpty'),
//                array('name' => 'Digits'),
//                array(
//                    'name' => 'Zend\Validator\Db\RecordExists',
//                    'options' => array(
//                        'table' => 'shujuxia_microhome_user',
//                        'field' => 'id',
//                        'adapter' => $this->adapter
//                    )
//                )
            ),
        )));

        $inputFilter->add($factory->createInput(array(
            'name'     => 'username',
            'required' => true,
            'filters'  => array(
                array('name' => 'StripTags'),
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array('name' => 'NotEmpty'),
//                array('name' => 'Digits'),
//                array(
//                    'name' => 'Zend\Validator\Db\RecordExists',
//                    'options' => array(
//                        'table' => 'shujuxia_microhome_info',
//                        'field' => 'id',
//                        'adapter' => $this->adapter
//                    )
//                )
            ),
        )));
        $inputFilter->add($factory->createInput(array(
            'name'     => 'img',
            'required' => true,
            'filters'  => array(
                array('name' => 'StringTrim'),
            ),
            'allow_empty'=>true,
//            'validators' => array(
//                array('name' => 'NotEmpty'),
//                array('name' => 'Digits'),
//                array(
//                    'name' => 'Zend\Validator\Db\RecordExists',
//                    'options' => array(
//                        'table' => 'shujuxia_microhome_info',
//                        'field' => 'id',
//                        'adapter' => $this->adapter
//                    )
//                )
//            ),
        )));
        $inputFilter->add($factory->createInput(array(
            'name'     => 'headurl',
            'required' => true,
            'filters'  => array(
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array('name' => 'NotEmpty'),
//                array('name' => 'Digits'),
//                array(
//                    'name' => 'Zend\Validator\Db\RecordExists',
//                    'options' => array(
//                        'table' => 'shujuxia_microhome_info',
//                        'field' => 'id',
//                        'adapter' => $this->adapter
//                    )
//                )
            ),
        )));

        return $inputFilter;
    }

}