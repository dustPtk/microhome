<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 14-11-12
 * Time: 下午4:59
 */

namespace Exam\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\TableGateway\AbstractTableGateway;

class ExamPaperTable extends AbstractTableGateway implements AdapterAwareInterface
{
    /**
     * Set db adapter
     *
     * @param Adapter $adapter
     * @return AdapterAwareInterface
     */
    protected $table = 'shujuxia_exam_exampaper';

    public function setDbAdapter(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->initialize();
    }

    /**
     * this method receive arraydata by the field id
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
    public function createPaper($data)
    {
        return $this->insert($data);
    }

    /**
     * @param $id
     * @return int
     */
    public function deletePaper($id)
    {
        return $this->delete(array('id'=>$id));
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function updatePaper($id,$data)
    {
        return $this->update($data,array('id'=>$id));
    }


}