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

class ExamContentTable extends AbstractTableGateway implements AdapterAwareInterface
{
    protected $table='shujuxia_exam_examcontent';
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
     *
     * this method recive data from table 'examcontent',
     * order to select table 'examTopic'
     *
     * @param $paperId
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function getByPaperID($paperId)
    {
       return $this->select(array('exampaper_id'=>$paperId));
    }


    public function getByPaperIdAndTopicID($paperId,$topicId)
    {
        return $this->select(array('exampaper_id'=>$paperId,'examtopic_id'=>$topicId));
    }
    /**
     *
     *
     * @param $data
     * @return int
     */
    public function createContent($data)
    {
        return $this->insert($data);
    }


    /**
     * @param $id
     * @return int
     */
    public function deleteExam($id)
    {
        return $this->delete(array('id'=>$id));
    }


}