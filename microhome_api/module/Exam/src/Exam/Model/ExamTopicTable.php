<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 14-11-12
 * Time: 下午4:56
 */

namespace Exam\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\TableGateway\AbstractTableGateway;

class ExamTopicTable extends AbstractTableGateway implements AdapterAwareInterface
{

    protected $table='shujuxia_exam_topic';
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
     * this method receive data by id
     *
     * @param $id
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getById($id)
    {
        return $this->select(array('id'=>$id));
    }

    /**
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function get()
    {
        return $this->select();
    }

    /**
     * @param $data
     * @return int
     */
    public function createTopic($data)
    {
        return $this->insert($data);
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function updateTopic($id,$data)
    {

        return $this->update($data,array('id'=>$id));
    }

    /**
     * @param $id
     * @return int
     */
    public function deleteTopic($id)
    {

        return $this->delete(array('id'=>$id));
    }
}