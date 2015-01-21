<?php
/**
 * Created by PhpStorm.
 * User: flame
 * Date: 14-12-16
 * Time: 上午9:52
 */

namespace MicroHome\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;


/**
 * 评论表对应的表关类
 *
 * Class CommentTable
 * @package MicroHome\Model
 */
class CommentTable extends AbstractTableGateway implements AdapterAwareInterface{
    protected $table = 'shujuxia_microhome_comment';

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
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getCommentByInfoId($info_id){
        $select = $this->sql->select()->where(array('info_id'=>$info_id));
        return $this->selectWith($select);
    }

    /**
     * delete data by info_id
     *
     * @param $info_id
     * @return int
     */
    public function deleteCommentByInfoId($info_id){
        return $this->delete(array('info_id'=>$info_id));
    }


    /**
     * select data by content,open_id,info_id
     *
     * @param $content
     * @param $open_id
     * @param $info_id
     * @return array|\ArrayObject|null
     *
     */
    public function getCommentByContent($content,$open_id,$info_id)
    {
        $rowset = $this->select(array('content'=>$content,'open_id'=>$open_id,'info_id'=>$info_id));
        return $rowset->current();
    }

    /**
     * @param $content
     * @param $open_id
     * @param $info_id
     * @return int
     */
    public function createComment($content,$open_id,$info_id,$time)
    {
        return $this->insert(array(
            'content'=>$content,
            'open_id'=>$open_id,
            'create_at'=>$time,
            'info_id'=>$info_id
        ));
    }

    /**
     * 删除某一条评论
     *
     * @param $id
     * @return int
     */
    public function deleteCommentById($id)
    {
        return $this->delete(array('id'=>$id));
    }

    /**
     * 过滤器，验证器，用来验证和过滤插入，更新的数据
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
            ),
        )));

        $inputFilter->add($factory->createInput(array(
            'name'     => 'content',
            'required' => true,
            'validators' => array(
                array('name' => 'NotEmpty'),
            ),
        )));
        $inputFilter->add($factory->createInput(array(
            'name'=>'open_id',
            'required'=>true,
            'validators'=>array(
                array('name'=>'NotEmpty')
            )
        )));
        return $inputFilter;
    }
}